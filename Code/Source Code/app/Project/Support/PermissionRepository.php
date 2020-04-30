<?php
/**
 * File: PermissionRepository.php
 * Created: 01-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Class PermissionRepository
 * @package Project\Support
 */
class PermissionRepository
{

    /**
     * Repository data
     *
     * @var array
     */
    public $data = array();

    /**
     * String delimiter when using toString()
     * @var string
     */
    public $string_delimiter = ', ';

    /**
     * Constructor
     *
     * @param string $file
     */
    public function __construct($file = 'permissions.json')
    {
        $this->data = json_decode(file_get_contents($file), true);
    }

    /**
     * Returns the permission list as a string
     *
     * @return string
     */
    public function toString()
    {
        return implode($this->string_delimiter, call_user_func_array(array($this, 'toArray'), func_get_args()));
    }

    /**
     * Returns the permissions as an array
     *
     * @return string
     */
    public function toArray()
    {
        $array = array();
        $args = func_get_args();
        if (!count($args)) {
            return $this->flatten($this->data);
        }

        foreach (func_get_args() as $name) {
            // Get the value based on the dot notation
            $array[] = $this->get($name, array());
        }

        return $this->flatten($array);
    }

    /**
     * Get an item from an array using "dot" notation.
     * Based on https://github.com/laravel/framework/blob/8724705dd514c2125dc1e39a5e04954e2c2b28f3/src/Illuminate/Support/Arr.php#L232
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $array = $this->data;
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Flatten the array
     *
     * @param $array
     * @return array
     */
    protected function flatten($array)
    {

        // flatten the array
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));

        $return = array();
        foreach ($it as $v) {
            $return[] = $v;
        }

        natsort($return);

        return array_values(array_unique($return));
    }

}
