<?php
/**
 * File: Snippet.php
 * Created: 31-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Exception;
use Html2Text\Html2Text;
use Project\Support\Snippets\Validator;
use Project\Support\StoryEngine\Parser;
use Story\Collection;
use Story\Error;
use Story\ORM;
use StoryEngine\StoryEngine;

/**
 * Class Snippet
 * @package Project\Models
 */
class Snippet extends ORM
{

    /**
     * @var string
     */
    protected static $table = 'snippets';
    protected static $tableAboutus='dev_aboutus';

    /**
     * Cache expiration in seconds (default: 31536000 = 1 year)
     *
     * @var int
     */
    protected static $cache_expires = 31536000;

    /**
     * Cache prefix name
     *
     * @var string
     */
    protected static $cache_prefix = 'snippet';

    /**
     * @var \Project\Support\Cache\File
     */
    protected $cache_repository;

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {

        $this->cache_repository = static::cacheInit();

        return parent::__construct($id);
    }

    /**
     * Initializes the cache repository
     *
     * @return \Project\Support\Cache\File
     */
    public static function cacheInit()
    {
        /** @var \Project\Support\Cache\File $cache */
        $cache = app('container')->make('Project\Support\Cache\CacheProviderInterface');
        $cache->setPrefix(static::$cache_prefix);
        $cache->setExpires(static::$cache_expires);

        return $cache;
    }

    /**
     * Returns the snippet by id and caches the model
     *
     * @param $id
     * @return mixed|null
     */
    public static function get($id)
    {
        // check if we have the cached version
        $cache_name = static::makeCacheName($id);
        // Init the cache repository
        $cache_repository = static::cacheInit();

        // If cached and valid model we return it
        if (($cached = $cache_repository->get($cache_name)) instanceof Snippet) {
            return $cached;
        }

        // No cache? we find the model and cache it
        $model = static::one(array('slug' => $id, 'status' => '1'));

        if ($model) {
            $cache_repository->put($cache_name, $model);
        }


        return $model;
    }

    /**
     * Makes a cache name based on slug
     *
     * @param $slug
     * @return string
     */
    public static function makeCacheName($slug)
    {
        return static::$cache_prefix . $slug;
    }

    /**
     * @param string $query
     * @param int $current
     * @param int $per_page
     *
     * @return array
     */
    public static function listSnippetsByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$table);

        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}slug{$i}", "{$tbl}.{$i}description{$i}", "{$tbl}.{$i}content_text{$i}");

        $queryWhere = query_to_where($query, $fields, '');

        return static::listSnippets($current, $per_page, $queryWhere);
    }

    public static function listAboutusByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$tableAboutus);

        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}author_name{$i}", "{$tbl}.{$i}show_type{$i}", "{$tbl}.{$i}content_text{$i}");

        $queryWhere = query_to_where($query, $fields, '');
        return static::listSnippets($current, $per_page, $queryWhere);
    }

    /**
     * @param $current
     * @param $per_page
     * @param null|array $queryWhere
     * @return array
     */
    public static function listSnippets($current, $per_page, $queryWhere = null)
    {
        try {
            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$table);
            $where = array();
            $params = array();

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
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;

            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            // build the query
            $sql = "SELECT {$tbl}.* "
                // user data
                . $sql_base
                . "\n ORDER BY {$db->i($tbl . '.slug')} ASC"
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


    public static function listaboutus($current, $per_page, $queryWhere = null)
    {
        try {
            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$tableAboutus);
            $where = array();
            $params = array();

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
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;

            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            // build the query
            $sql = "SELECT {$tbl}.* "
                // user data
                . $sql_base
                . "\n ORDER BY {$db->i($tbl . '.author_name')} ASC"
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
     * Creates a snippet from form
     *
     * @param $input
     * @return bool
     */
    public static function createFromForm(array $input)
    {
        $validator = Validator::create($input);

        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();
                $input = $validator->data();
                $content_text = new Html2Text($input['content']);
                $model = new static;
                $model->set(
                    array(
                        'slug'         => $input['slug'],
                        'description'  => $input['description'],
                        'content'      => $input['content'],
                        'content_text' => $content_text->getText(),
                        'status'       => isset($input['status']) ? (int)$input['status'] : 0
                    )
                );
                $model->save();
                static::$db->pdo->commit();
                event('snippet.created', $model);

                return true;
            } catch (Exception $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);
            }
        }

        return $validator;
    }

    /**
     * Returns the file list
     *
     * @param int $limit
     * @param null|callable $generator
     * @return array|mixed
     */
    public static function getSnippetList($limit = 1000, $generator = null)
    {

        $query = array('sql' => '', 'values' => array());



        // show only the enabled assets
        $query['sql'] .= ' ' . static::$db->i('status') . ' = 1';

        // we have query, get the assets filtered
        $items = static::listSnippets(
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
     * Save the current object to the database
     *
     * @param bool $force_insert
     *
     * @return $this
     */
    public function save($force_insert = false)
    {
        if (!$force_insert) {
            $this->cache_repository->forget($this->getCacheName());
        }

        return parent::save($force_insert);
    }

    /**
     * Returns the cache name for the model
     *
     * @return string
     */
    public function getCacheName()
    {
        return static::$cache_prefix . $this->slug;
    }

    /**
     * Content attribute accessor. Also executes the php echo statements like {{ time() }}
     * @param $value
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
     * Updates the existing snippet from form
     *
     * @param array $input
     * @return bool|static
     */
    public function updateFromForm(array $input)
    {
        $validator = Validator::update($input, $this);

        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();
                $input = $validator->data();
                $content_text = new Html2Text($input['content']);

                $this->set(
                    array(
                        'slug'         => $input['slug'],
                        'description'  => $input['description'],
                        'content'      => $input['content'],
                        'content_text' => $content_text->getText(),
                        'status'       => isset($input['status']) ? (int)$input['status'] : 0
                    )
                );
                $this->save();
                static::$db->pdo->commit();
                event('snippet.updated', $this);

                return true;
            } catch (Exception $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);
            }
        }

        return $validator;

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
        if (!$id) {
            $this->cache_repository->forget($this->getCacheName());
        }

        return parent::delete($id);
    }

    public function getSnippetCode()
    {
        return Parser::OPENING_TAG . ' snippet("' . $this->slug . '") ' . Parser::CLOSING_TAG;
    }

    /**
     * Update the current object in the database table
     *
     * @param array $data
     *
     * @return boolean
     */
    protected function update(array $data)
    {

        $this->cache_repository->forget($this->getCacheName());

        return parent::update($data);
    }
}
