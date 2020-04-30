<?php
/**
 * File: File.php
 * Created: 06-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Cache;

class File extends CacheAbstract
{
    /**
     * @var int
     */
    protected $expires = 15;

    /**
     * Path to store the cache files
     *
     * @var string
     */
    protected $cache_path = '/tmp';


    /**
     * Clear the expired cache items
     *
     * @return bool
     */
    public function clearExpired()
    {
        $files = glob($this->cache_path . DIRECTORY_SEPARATOR . $this->generateFileName(false, '*'));
        foreach ($files as $file) {
            $data = explode('_', $file, 3);
            if (isset($data[1]) && (int) $data[1] < time()) {
                @unlink($file);
            }
        }

        return count($files);
    }

    /**
     * Removes the data from the cache
     *
     * @param $name
     *
     * @return bool
     */
    public function forget($name)
    {
        $files = glob($this->cache_path . DIRECTORY_SEPARATOR . $this->generateFileName($name, '*'));
        foreach ($files as $file) {
            @unlink($file);
        }

        return count($files);
    }

    /**
     * Removes all the cached data
     *
     * @param $name
     *
     * @return bool
     */
    public function forgetAll($name)
    {
        $files = glob($this->cache_path . DIRECTORY_SEPARATOR . $this->generateFileName('*', '*'));
        foreach ($files as $file) {
            @unlink($file);
        }

        return count($files);
    }

    /**
     * Returns a cached asset
     *
     * @param $name
     *
     * @return mixed
     */
    public function get($name)
    {
        $this->sweep();
        // check if exists and not expired
        if (!$this->expired($name) && $file = $this->fileExists($name)) {

            return unserialize(file_get_contents($file));
        } else {
            $this->forget($name);
        }

        return null;
    }

    /**
     * Saved the data in the cache
     *
     * @param $name
     * @param $data
     *
     * @return bool
     */
    public function put($name, $data)
    {
        $this->sweep();
        // we check if cache with the same name exists
        if ($this->fileExists($name)) {
            $this->forget($name);
        }
        $filename = $this->cache_path .
            DIRECTORY_SEPARATOR .
            $this->generateFileName($name, time() + $this->getExpires());

        $data = serialize($data);

        return file_put_contents($filename, $data);
    }

    /**
     * @return string
     */
    public function getCachePath()
    {
        return $this->cache_path;
    }

    /**
     * Generates the file name of the cache file
     *
     * @param $name
     * @param $expires
     *
     * @return string
     */
    protected function generateFileName($name, $expires)
    {
        $hash = '*';

        if ($name) {
            $hash = md5($name);

        }

        return $this->getPrefix() . '_' . $expires . '_' . $hash;
    }

    /**
     * Returns the file name if exists
     *
     * @param $name
     *
     * @return string
     */
    public function fileExists($name)
    {
        return current(glob($this->cache_path . DIRECTORY_SEPARATOR . $this->generateFileName($name, '*')));
    }

    /**
     * Returns true if cache item is expired
     *
     * @param $name
     *
     * @return bool
     */
    public function expired($name)
    {
        if ($file = $this->fileExists($name)) {

            $filename = basename($file);

            // get the timestamp
            $data = explode('_', $filename, 3);

            if (!isset($data[1]) || $data[1] < time()) {
                return true;
            }
        }
        return false;
    }

    /**
     * There is a 2% chance
     *
     * @return int
     */
    private function sweep()
    {
        if (mt_rand(1, 100) <= 2) {
            $this->clearExpired();
        }
        return false;
    }
}
