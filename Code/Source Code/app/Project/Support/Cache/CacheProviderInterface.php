<?php
/**
 * File: CacheProviderInterface.php
 * Created: 29-10-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Cache;

/**
 * Interface CacheProviderInterface
 *
 * @package StoryEngine\Interfaces
 */
interface CacheProviderInterface
{
    /**
     * Clear the expired cache items
     *
     * @return bool
     */
    public function clearExpired();

    /**
     * Removes the data from the cache
     *
     * @param $name
     *
     * @return bool
     */
    public function forget($name);

    /**
     * Removes all the cached data
     *
     * @param $name
     *
     * @return bool
     */
    public function forgetAll($name);

    /**
     * Returns a cached asset
     *
     * @param $name
     *
     * @return mixed
     */
    public function get($name);

    /**
     * Returns the expires value
     *
     * @return int
     */
    public function getExpires();

    /**
     * Saved the data in the cache
     *
     * @param $name
     * @param $data
     *
     * @return bool
     */
    public function put($name, $data);

    /**
     * Sets the default expire value in seconds
     *
     * @param $seconds
     *
     * @return int
     */
    public function setExpires($seconds);

    /**
     * Sets the cache provider options
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options = array());
}
