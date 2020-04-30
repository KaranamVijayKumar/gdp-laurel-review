<?php
/**
 * File: Page.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\Error;
use Story\NotFoundException;
use Story\ORM;

/**
 * Class Page
 *
 * @package Project\Models
 */
class Page extends ORM
{
    /**
     * Index page slug name
     */
    const INDEX_PAGE_SLUG = 'index';

    /**
     * @var array
     */
    public static $has_many = array(
        'content' => 'Project\Models\PageContent',
        'meta'    => 'Project\Models\PageMeta',
        'data'    => 'Project\Models\PageData',
    );

    /**
     * @var array
     */
    public static $not_extendable_pages = array(
        '',
        'account/activate',
        'account/changepassword',
        'account/logout',
        'account/reset',
        'account/submissions/checkout',
        'account/submissions/sign',
        'account/submissions/withdraw',
        'account/subscriptions/cancel',
        'account/subscriptions/checkout',
        'account/subscriptions/show',
        'issues/checkout',
        'issues/order',
        'issues/toc',
        'chapbooks/checkout',
        'chapbooks/order',
        'chapbooks/toc',
        'news/article',
        'paypal/ipn',
        'cart/checkout',
        'cart/empty',
        'cart/remove',
        404,
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'page_id';

    /**
     * @var string
     */
    protected static $table = 'pages';

    /**
     * @var \Project\Support\Cache\File
     */
    public $page_cache;

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {

        $this->page_cache = app('container')->make('Project\Support\Cache\CacheProviderInterface');
        $this->page_cache->setPrefix('page');
        $this->page_cache->setExpires(600);

        require_once SP . 'Project/Support/Events/page_events.php';

        return parent::__construct($id);
    }

    /**
     * Returns the file list
     *
     * @param int $limit
     * @param null|callable $generator
     * @return array|mixed
     */
    public static function getPageList($limit = 1000, $generator = null)
    {

        $query = array('sql' => '', 'values' => array());



        // show only the enabled assets
        $query['sql'] .= ' ' . static::$db->i('status') . ' = 1';

        // we have query, get the assets filtered
        $items = static::listPages(
            1,
            $limit,
            $query
        );

        if (is_callable($generator)) {
            return call_user_func($generator, $items);
        } else {
            return $items;
        }

    }

    /**
     * @param string $query
     * @param int $current
     * @param int $per_page
     *
     * @param string|null $locale
     * @return array
     */
    public static function listPagesByQuery($query, $current, $per_page, $locale = null)
    {

        $tbl = static::$db->i(static::$table);
        $tbl_content = static::$db->i(PageContent::getTable());
        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}slug{$i}", "{$tbl_content}.{$i}title{$i}", "{$tbl_content}.{$i}content_text{$i}");

        $queryWhere = query_to_where($query, $fields, '');

        return static::listPages($current, $per_page, $queryWhere, $locale);
    }

    /**
     * @param $current
     * @param $per_page
     * @param null|array $queryWhere
     * @param null|string $locale
     * @return array
     */
    public static function listPages($current, $per_page, $queryWhere = null, $locale = null)
    {
        try {
            static::$db->pdo->beginTransaction();

            if (!$locale) {
                $locale = app('locale');
            }

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$table);
            $tbl_content = static::$db->i(PageContent::getTable());
            $where = array();
            $params = array('content', $locale);

            // check if we have query, and include that in the sql
            $query = '';
            if ($queryWhere) {

                $params = array_merge($params, $queryWhere['values']);

                if ($where) {
                    $query .= ' AND ';
                } else {
                    $query .= ' WHERE ';
                }

                $query .= "(" . $queryWhere['sql'] . ")";
            }

            $sql_base =
                // from
                "\n FROM {$tbl}"
                // join
                . "\n LEFT JOIN {$tbl_content} ON {$db->i($tbl_content.'.page_id')} = {$db->i($tbl.'.id')}"
                . "\n AND {$db->i($tbl_content.'.name')} = ? AND {$db->i($tbl_content.'.locale')} = ?"
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;


            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            // build the query
            $sql = "SELECT {$tbl}.*, {$db->i($tbl_content.'.title')}, {$db->i($tbl_content.'.content_text')} "
                // user data
                . $sql_base
                . "\n ORDER BY {$db->i($tbl_content . '.title')}, {$db->i($tbl . '.slug')} ASC"
                . $sql_limit;


            // execute the query
            $items = static::$db->fetch($sql, $params);
            foreach ($items as $id => $row) {
                $items[$id] = new static($row);
            }
            $items = new Collection($items);

            // count sql
            $count_sql = "SELECT COUNT(DISTINCT {$db->i($tbl .'.id')})"
                . "\n" . $sql_base;

            $count = static::$db->column($count_sql, $params);

            // commit
            static::$db->pdo->commit();

            return array('total' => $count, 'items' => $items);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Finds a page by slug and caches the data
     *
     * @param $slug
     * @param $params
     *
     * @return Page
     * @throws NotFoundException
     */
    public static function findBySlug($slug, $params)
    {

        // attempt to get the page
        /** @var Page $page */
        $page = Page::one(array_merge(array('slug' => $slug), $params));

        if (!$page) {

            throw new NotFoundException('Page not found. [' . $slug . ']');
        }

        return $page->getCached();

    }

    /**
     * Create a page from form
     *
     * @param $input
     * @return static
     */
    public static function createFromForm($input)
    {

        try {
            static::$db->pdo->beginTransaction();
            require_once SP . 'Project/Support/Pages/pages_helpers.php';

            $page = new static;
            $page->set(
                array(
                    'slug'    => get_page_slug_from_input($input),
                    'status'  => $input['status'],
                    'locked'  => '0',
                    'created' => time(),
                )
            );

            $page->save(true);

            // add the sections
            PageContent::createContents($page, $input, app('locale'));

            static::$db->pdo->commit();
            event('page.created', $page);

            return $page;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
            return false;
        }
    }

    /**
     * Returns the available system pages that can be extended by the user
     *
     * @return array
     */
    public static function getSystemPages()
    {
        // get the routes
        $pages = array_keys(require SP . '/config/routes.php');

        $not_allowed = static::$not_extendable_pages;

        // filter out the private pages, that cannot be extended
        $pages = array_filter($pages, function ($page) use ($not_allowed) {

            return !in_array($page, $not_allowed);

        });


        // filter out the admin && regex pages
        $admin_path = config('admin_path');
        $pages = array_filter($pages, function ($page) use ($admin_path) {

            return !starts_with($page, array($admin_path, '/'));

        });

        // we add the index page
        $pages[] = static::INDEX_PAGE_SLUG;

        // sort
        natsort($pages);

        // get the slugs from the db and diff
        $existing_pages = static::lists('slug', null);

        $list = array_values(array_diff($pages, $existing_pages));

        return array_combine($list, $list);
    }

    /**
     * Updates the slug of the page
     *
     * @param $slug
     * @return $this
     */
    public function updateSlug($slug)
    {
        $this->slug = $slug;

        return $this->save();
    }

    /**
     * Caches the current page if needed
     *
     *
     * @return Page
     */
    public function getCached()
    {

        // check if we have the page in the cache
        $cache_name = $this->getCacheName();

        $cached_page = false;
        if ($cache_name) {
            $cached_page = $this->page_cache->get($cache_name);
        }


        if ($cached_page) {
            return $cached_page;

        } else {
            // we don't have the page, so we cache it
            $this->data->load();
            $this->meta->load();
            $this->content->load();

            $this->page_cache->put($cache_name, $this);

            return $this;
        }
    }

    /**
     * Save the current object to the database
     *
     * @param bool $force_insert
     *
     * @return $this
     */
    public function save($force_insert = false)
    {
        // page saved event
        event('page.saved', $this);

        return parent::save($force_insert);
    }

    /**
     * Delete the current object (and all related objects) from the database
     *
     * @param int $id to delete
     *
     * @return int
     */
    public function delete($id = null)
    {
        // page deleted event
        event('page.deleted', $this);

        return parent::delete($id);
    }


    /**
     * Attempts to return the localized content for the page
     *
     * @param string $default_locale
     *
     * @param bool $fallback
     * @return Collection
     * @throws NotFoundException
     */
    public function getLocalizedContent($default_locale = 'en', $fallback = true)
    {

        $this->content->load();
        /** @var Collection $content */
        $content = $this->content->findAllBy('locale', app('locale'));

        if (!count($content)) {
            // we spin through the existing languages and get a matching one
            foreach (config('languages') as $locale) {
                $content = $this->content->findAllBy('locale', $locale);
                if ($content) {
                    break;
                }
            }
        }

        if ($fallback) {
            // still no match? we show the default localized content
            if (!count($content)) {
                $content = $this->content->findAllBy('locale', $default_locale);
            }

            // we give up, and throw an not found
            if (!count($content)) {
                throw new NotFoundException('Page not found. [locale=' . $default_locale . ']');
            }

        }

        return $content;
    }

    /**
     * Returns the cache name of the page
     *
     * @return string|false
     */
    public function getCacheName()
    {
        if (isset($this->attributes['id'])) {
            return 'page_' . $this->id;
        } else {
            return false;
        }

    }

    /**
     * Updates the page from form
     *
     * @param $data
     * @return bool|Page
     */
    public function updateFromForm($data)
    {
        try {
            static::$db->pdo->beginTransaction();

            // Update the content
            PageContent::updateContentForPage($this, $data);

            // Update the meta
            PageMeta::updateForPage($this, $data);

            // Lastly we update the page status & modified timestamp
            $this->status = $data['status'];
            $this->modified = time();
            $return = $this->save();

            static::$db->pdo->commit();

            // remove the cache
            event('page.saved', $this);

            event('page.updated', $this);

            return $return;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }
}
