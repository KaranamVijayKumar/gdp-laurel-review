<?php
/**
 * File: AbstractPublication.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Interfaces\Publication;
use Project\Interfaces\PublicationContent;
use Project\Interfaces\PublicationFile;
use Project\Interfaces\PublicationToc;
use Project\Interfaces\PublicationTocContent;
use Project\Interfaces\PublicationTocTitle;
use Story\ORM;
use StoryCart\CartItemRepository;
use StoryEngine\StoryEngine;
use Project\Services\Cart\OrderableInterface;
use Project\Services\Cart\OrderItemProcessableInterface;
use Project\Support\Orders\LinkableInterface;
use Project\Support\Orders\ShippableInterface;
use Story\Collection;
use Story\Error;

/**
 * Class AbstractPublication
 * @package Project\Models
 */
abstract class AbstractPublication extends ORM implements
    Publication,
    OrderableInterface,
    ShippableInterface,
    LinkableInterface,
    OrderItemProcessableInterface
{
    /**
     * Publication type
     */
    const TYPE = 'publication';

    /**
     * @var PublicationFile
     */
    public static $publication_file_repository = '\Project\Interfaces\PublicationFile';

    /**
     * @var PublicationContent
     */
    public static $publication_content_repository = '\Project\Interfaces\PublicationContent';

    /**
     * @var PublicationTocContent
     */
    public static $publication_toc_content_repository = '\Project\Interfaces\PublicationTocContent';

    /**
     * @var PublicationTocTitle
     */
    public static $publication_toc_title_repository = '\Project\Interfaces\PublicationTocTitle';

    /**
     * @var PublicationToc
     */
    public static $publication_toc_repository = '\Project\Interfaces\PublicationToc';

    /**
     * @var \Project\Support\Cache\File
     */
    protected $publication_cache;

    /**
     * This will set to true if this model updated the inventory already
     *
     * @var bool
     */
    public $inventory_updated = false;

    /**
     * @var array
     */
    public $highlights;

    /**
     * @param int $id
     */
    public function __construct($id = 0)
    {

        $this->publication_cache = app('container')->make('Project\Support\Cache\CacheProviderInterface');
        $this->publication_cache->setPrefix(static::TYPE);
        $this->publication_cache->setExpires(600);

        return parent::__construct($id);
    }

    /**
     * Creates the cart item model from the cart item
     *
     * @param \StoryCart\CartItemRepository $item
     *
     * @return \StoryCart\OrderItemRepository
     */
    public static function createFromCart(CartItemRepository $item)
    {
        $model = new OrderItem;

        $model->set(
            array(
                'orderable_id'   => $item->type_id,
                'orderable_type' => $item->type,
                'item_data'      => $item->type_payload,
                'quantity'       => $item->quantity,
                'price'          => $item->price,
                'tax'            => $item->tax,
                'currency'       => $item->currency,

            )
        );

        return $model;
    }

    /**
     * Creates the publication from user input (form)
     *
     * @param $data
     *
     * @return bool|static
     */
    public static function createFromForm($data)
    {

        try {

            static::$db->pdo->beginTransaction();

            // create the publication
            $publication = new static;
            $publication->set(
                array(
                    'slug'      => slug($data['title']),
                    'title'     => $data['title'],
                    'inventory' => (int)$data['inventory'],
                    'status'    => isset($data['status']) ? (int)$data['status'] : null,
                )
            );

            $publication->save();

            $file_repo = static::$publication_file_repository;
            $content_repo = static::$publication_content_repository;

            // create the content
            $content_repo::createContentForPublicationFromForm($publication, $data);

            // resize and store the file
            $file_repo::createPublicationCoverImageFromForm($publication, $data);

            // commit
            static::$db->pdo->commit();

            return $publication;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }

    /**
     * Returns the current publication highlights
     *
     * @param int $total Total number of highlights
     *
     * @return bool|\Project\Support\Publications\TocContentCollection
     */
    public static function getCurrentHighlights($total = 3)
    {

        // get the current publication
        $latest = static::fetch(array('status' => '1'), 1, 0, array('created' => 'desc'));

        if (!$latest || !count($latest)) {
            return false;
        }
        $latest = reset($latest);
        $latest->load();

        /** @var \Project\Support\Publications\TocContentCollection $highlights */
        $highlights = $latest->related(
            'toc_contents',
            array('highlight' => '1', 'status' => '1'),
            $total,
            0,
            array('RAND()')
        );
        $highlights->loadWithTitlesAndAuthors();


        if (!count($highlights)) {
            return array($latest, new Collection);
        }

        return array($latest, $highlights);
    }

    /**
     * Returns the last publication
     *
     * @return \stdClass
     */
    public static function getLast()
    {
        $file_repo = static::$publication_file_repository;
        $content_repo = static::$publication_content_repository;

        $i = static::$db->i;
        $db = static::$db;
        $tbl = static::$db->i(static::$table);
        $files_tbl = static::$db->i($file_repo::getTable());
        $content_tbl = static::$db->i($content_repo::getTable());
        $created = static::$db->i('created');

        $params = array(get_called_class(), current($content_repo::getRequiredSections()));

        $sql = "SELECT {$tbl}.*, {$files_tbl}.{$i}storage_name{$i}, " .
            "{$content_tbl}.{$i}content_text{$i}, {$content_tbl}.{$i}content{$i}\n" .
            "FROM {$tbl}\n" .
            "LEFT JOIN {$files_tbl} ON {$tbl}.{$i}id{$i} = {$files_tbl}.{$i}" . static::TYPE . "able_id{$i} " .
            "AND {$files_tbl}.{$i}" . static::TYPE . "able_type{$i} = ? \n" .
            "LEFT JOIN {$content_tbl} ON {$tbl}.{$i}id{$i} = {$content_tbl}.{$i}" . static::TYPE . "_id{$i} " .
            "AND {$content_tbl}.{$i}name{$i} = ?\n" .
            "WHERE {$db->i($tbl.'.status')} = 1\n" .
            "ORDER BY {$created} DESC LIMIT 1";

        return static::$db->row($sql, $params);
    }

    /**
     * List the publication matching the query and sort by date desc
     *
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     *
     * @return array
     */
    public static function listByQuery($query, $current, $per_page)
    {

        // 1. get the items
        $where = query_to_where(
            $query,
            array('slug', static::$table . static::$db->i . '.' . static::$db->i . 'title', 'content_text'),
            static::$db->i
        );

        return static::listPublications($current, $per_page, $where);
    }

    /**
     * List the publications sorted by date descending
     *
     * @param int   $current
     * @param int   $per_page
     *
     * @param array $where
     *
     * @return array
     */
    public static function listPublications($current, $per_page, array $where = null)
    {

        $file_repo = static::$publication_file_repository;
        $content_repo = static::$publication_content_repository;

        $offset = $per_page * ($current - 1);

        $i = static::$db->i;
        $tbl = static::$db->i(static::$table);
        $files_tbl = static::$db->i($file_repo::getTable());

        $content_tbl = static::$db->i($content_repo::getTable());
        $created = static::$db->i('created');


        $params = array(get_called_class(), current($content_repo::getRequiredSections()));

        if ($where) {
            $params = array_merge($params, $where['values']);
        }

        // 1. get the items
        $sql = "SELECT {$tbl}.*, {$files_tbl}.{$i}storage_name{$i}, " .
            "{$content_tbl}.{$i}content_text{$i}, {$content_tbl}.{$i}content{$i}\n" .
            "FROM {$tbl}\n" .
            "LEFT JOIN {$files_tbl} ON {$tbl}.{$i}id{$i} = {$files_tbl}.{$i}" . static::TYPE . "able_id{$i} " .
            "AND {$files_tbl}.{$i}" . static::TYPE . "able_type{$i} = ? \n" .
            "LEFT JOIN {$content_tbl} ON {$tbl}.{$i}id{$i} = {$content_tbl}.{$i}" . static::TYPE . "_id{$i} " .
            "AND {$content_tbl}.{$i}name{$i} = ? \n" .

            ($where ? 'WHERE ' . $where['sql'] : '') . ' ' .
            "ORDER BY {$created} DESC LIMIT {$offset}, {$per_page}";


        $items = static::$db->fetch($sql, $params);
        if (count($items)) {
            foreach ($items as $k => $item) {
                $items[$k] = new static($item);
            }
        }
        $items = new Collection($items);

        // 2. count the total items
        $countSql = "SELECT COUNT(*) FROM {$tbl}" .
            "LEFT JOIN {$files_tbl} ON {$tbl}.{$i}id{$i} = {$files_tbl}.{$i}" . static::TYPE . "able_id{$i} " .
            "AND {$files_tbl}.{$i}" . static::TYPE . "able_type{$i} = ? \n" .
            "LEFT JOIN {$content_tbl} ON {$tbl}.{$i}id{$i} = {$content_tbl}.{$i}" . static::TYPE . "_id{$i} " .
            "AND {$content_tbl}.{$i}name{$i} = ? \n" .
            ($where ? ' WHERE ' . $where['sql'] : '');

        $total = static::$db->column($countSql, $params);


        // 3. return the items
        return array('total' => $total, 'items' => $items);
    }

    /**
     * Get latest publications with highlights
     *
     * @param int   $limit
     * @param int   $content_limit
     * @param array $where
     *
     * @return Collection
     */
    public static function withTocHighlights($limit = 3, $content_limit = 3, array $where = null)
    {

        $i = static::$db->quoteIdentifier;

        $file_repo = static::$publication_file_repository;
        $content_repo = static::$publication_content_repository;
        $toc_content_repo = static::$publication_toc_content_repository;

        $content = static::$db->i($content_repo::getTable());
        $files = static::$db->i($file_repo::getTable());
        $tbl = static::$db->i(static::$table);

        $params = array(get_called_class(), current($content_repo::getRequiredSections()));

        if ($where) {
            $params = array_merge($params, $where['values']);
        }

        $sql = "SELECT {$tbl}.*, {$i->get($files . '.storage_name')}, " .
            "{$i->get($content . '.content_text')}, {$i->get($content .'.content')}\n" .
            "FROM {$tbl}\n" .
            "LEFT JOIN {$files} ON {$i->get($tbl .'.id')} = {$i->get($files .'.'. static::TYPE .'able_id')} " .
            "AND {$i->get($files .'.'. static::TYPE .'able_type')} = ? \n" .
            "LEFT JOIN {$content} ON {$i->get($tbl .'.id')} = {$i->get($content . '.'. static::TYPE .'_id')} " .
            "AND {$i->get($content . '.name')} = ?\n" .

            ($where ? 'WHERE ' . $where['sql'] : '') . ' ' .
            "ORDER BY {$i->created} DESC LIMIT {$limit}";

        $items = static::$db->fetch($sql, $params);

        if ($items) {
            foreach ($items as $k => $v) {
                $items[$k] = new static($v);
            }
        }
        $items = new Collection($items);

        // We get the random highlights for the publications (toc_contents)
        if (count($items)) {
            $highlights = $toc_content_repo::getRandomHighlighted($items, $content_limit);
            // associate the contents with the publications
            if ($highlights) {
                foreach ($highlights as $highlight) {
                    $item = $items->findBy('id', $highlight->{static::TYPE . '_id'});
                    if ($item) {
                        if (!isset($item->highlights)) {
                            $item->highlights = new Collection(array($highlight));
                        } else {
                            $item->highlights->push($highlight);
                        }
                    }
                }
            } else {
                // We add an empty collection to those items that doesn't have highlights
                foreach ($items as $item) {
                    if (!isset($item->highlights)) {
                        $item->highlights = new Collection;
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Returns the inventory
     *
     * @param int|AbstractPublication $id
     *
     * @return int
     */
    public static function getInventory($id)
    {
        $inventory = 0;

        if (!$id) {
            return (int)$inventory;
        } elseif (!is_numeric($id)) {
            $id = $id->key();
        }


        /** @var AbstractPublication $model */
        $model = static::one(array('id' => $id));

        if ($model) {
            $inventory = $model->inventory;
        }

        if ($inventory) {
            $inventory = $inventory + 1;
        }

        return (int)$inventory;
    }

    /**
     * Removes the model and deletes the related files
     *
     * @return bool|int
     */
    public function deleteWithFiles()
    {

        try {

            static::$db->pdo->beginTransaction();

            $file_repo = static::$publication_file_repository;
            // get the cover image if not set
            if (!isset($this->cover_image)) {
                /** @var PublicationFile $cover_image */
                $this->cover_image = $file_repo::one(
                    array(
                        static::TYPE . 'able_id'   => $this->id,
                        static::TYPE . 'able_type' => get_class($this)
                    )
                );
            }

            $cover_image = $this->cover_image;

            if ($cover_image) {

                $storage_name = $cover_image->getStorageName();

                $cover_image->delete();

                $file_path = $file_repo::getCoverStoragePath() . $storage_name;
                @unlink($file_path);
            }

            $this->removeCached();

            $return = $this->delete();
            static::$db->pdo->commit();

            return $return;
        } catch (\Exception $e) {

            if (static::$db->pdo->inTransaction()) {
                static::$db->pdo->rollBack();
            }
            Error::exception($e);
        }

        return false;
    }

    /**
     * Removes the cached data for the publication
     *
     * @return bool
     */
    public function removeCached()
    {

        return $this->publication_cache->forget(static::TYPE . '_' . $this->id);
    }

    /**
     * Content attribute accessor. Also executes the php echo statements like {{ time() }}
     *
     * @param $value
     *
     * @return mixed
     */
    public function getContentAttribute($value)
    {
        /** @var StoryEngine $engine */
        $engine = app('storyengine');
        $parser = $engine->getParser();
        $parser->execute($value);

        return $value;
    }

    /**
     * Caches the current page if needed
     *
     *
     * @return Publication
     */
    public function getCached()
    {

        // check if we have the page in the cache
        $cache_name = static::TYPE . '_' . $this->id;
        $file_repo = static::$publication_file_repository;
        $cached = $this->publication_cache->get($cache_name);

        if ($cached) {
            return $cached;
        } else {
            // get the file
            $this->cover_image = $file_repo::one(
                array(
                    static::TYPE . 'able_id'   => $this->id,
                    static::TYPE . 'able_type' => get_class($this)
                )
            );

            // get all the contents
            $this->contents->load();

            // get the toc and load the titles
            $this->related('toc', array(), 0, 0, array('order' => 'asc'))->loadWithTitles();

            $this->publication_cache->put($cache_name, $this);

            return $this;
        }
    }

    /**
     * Returns the price
     *
     * @return mixed|null
     */
    public function getPrice()
    {
        if ($this->isLatest()) {

            return config('latest_' . static::TYPE . '_price');
        } else {

            return config('back_' . static::TYPE . '_price');
        }
    }

    /**
     * Updates the publication from user data
     *
     * @param array $data
     *
     * @return $this|bool
     */
    public function updateFromForm(array $data)
    {

        try {

            static::$db->pdo->beginTransaction();

            $this->removeCached();

            // update the publication
            $this->set(
                array(
                    'slug'   => slug($data['title']),
                    'title'  => $data['title'],
                    'inventory' => isset($data['inventory']) ? (int)$data['inventory'] : $this->inventory,
                    'status' => isset($data['status']) ? (int)$data['status'] : null,
                )
            );



            unset($this->changed['cover_image']);// this is the cover image, we have no such column in the db
            $this->save();

            $file_repo = static::$publication_file_repository;
            $content_repo = static::$publication_content_repository;

            // update the publication content
            $content_repo::updateContentsForPublication($this, $data);

            // update the publication file
            $file_repo::updatePublicationCoverImageFromForm($this, $data);

            // commit
            static::$db->pdo->commit();


            return $this;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }

    /**
     * Returns true if the current publication is the latest one
     *
     * @return bool
     */
    public function isLatest()
    {

        static $latest = false;

        if ($latest === false) {
            // get the latest and compare the id with the current one
            $latest = static::all(array('status' => '1'), 1, 0, array('created' => 'desc'));
        }

        return $this->id && current($latest)->id === $this->id;
    }

    /**
     * Returns the order type like: Issue, Chapbook, etc.
     * @return string
     */
    abstract public function getOrderType();

    /**
     * Returns the order assets
     *
     * @return mixed
     */
    public function getCartAssets()
    {
        return null;
    }

    /**
     * Returns the order payload
     *
     * @return mixed
     */
    public function getCartPayload()
    {
        return $this;
    }

    /**
     * Get the orderable name
     * @return string
     */
    public function getName()
    {
        return $this->title;
    }


    /**
     * Called when an item is removed from the cart
     *
     * @param CartItemRepository $model
     *
     * @return mixed
     */
    public function removeFromCart(CartItemRepository $model)
    {
        return null;
    }

    /**
     * @return boolean
     */
    public function canLink()
    {
        return is_numeric($this->key());
    }

    /**
     * @return string
     */
    abstract public function getAdminLink();

    /**
     * @return string
     */
    abstract public function getLink();

    /**
     * Processes the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function processOrderItem(OrderItem $item)
    {
        // Find the publication if exists
        if (!$item->orderable_id) {
            return $item;
        }
        /** @var AbstractPublication $publication */
        $publication = static::one(array('id' => $item->orderable_id));
        $item_data = $item->item_data;
        if ($publication && !$item_data->inventory_updated) {
            $item_data->inventory_updated = true;
            $publication->inventory = (int)$publication->inventory - (int)$item->quantity;
            $item->item_data = $item_data;

            $publication->save();
            $item->save();
        }

        return $item;
    }
}
