<?php
/**
 * File: Menu.php
 * Created: 27-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class Menu
 * @package Project\Models
 */
class Menu extends ORM
{
    /**
     * @var \Project\Support\Cache\File
     */
    public static $menu_cache;

    /**
     * @var int
     */
    public static $cache_expires = 10;

    /**
     * @var string
     */
    protected static $table = 'menusv1';

    /**
     * Returns all the menus and caches them
     *
     * @param array $where
     * @param int   $limit
     * @param int   $offset
     * @param array $order
     *
     * @return array|mixed
     */
    public static function allCached($where = null, $limit = 0, $offset = 0, $order = null)
    {

        if (!static::$menu_cache) {
            static::initCache();
        }

        // we generate the name based on the function parameters
        $cache_name = json_encode(func_get_args());

        $cached = static::$menu_cache->get($cache_name);

        if ($cached) {
            return $cached;
        }

        $cached = parent::all($where, $limit, $offset, $order);

        foreach ($cached as $key => $menu) {
            $cached[$key] = new static($menu);
        }

        static::$menu_cache->put($cache_name, $cached);

        return $cached;
    }

    /**
     * Initializes the cache
     *
     * @return \Project\Support\Cache\File
     */
    public static function initCache()
    {
        static::$menu_cache = app('container')->make('Project\Support\Cache\CacheProviderInterface');
        static::$menu_cache->setPrefix('menu');
        static::$menu_cache->setExpires(static::$cache_expires); // we cache menus for 10 seconds

        return static::$menu_cache;
    }

    /**
     * Html attributes accessor
     *
     * @return array
     */
    public function getHtmlAttributesAttribute()
    {

        return json_decode($this->attributes['html_attributes'], true);
    }

    /**
     * Url params accessor
     *
     * @return mixed
     */
    public function getUrlParamsAttribute()
    {

        return json_decode($this->attributes['url_params'], true);
    }
}
