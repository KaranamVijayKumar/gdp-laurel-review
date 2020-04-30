<?php
/**
 * File: CacheAbstract.php
 * Created: 31-10-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Cache;


/**
 * Class CacheAbstract
 *
 * @package Project\Support\Cache
 */
abstract class CacheAbstract implements CacheProviderInterface
{
    /**
     * Prefixing the cache items
     */
    protected $prefix;

    /**
     * @var int
     */
    protected $expires = 15;

    /**
     * Returns the expires value
     *
     * @return int
     */
    public function getExpires()
    {

        return $this->expires;
    }

    /**
     * Sets the default expire value in seconds
     *
     * @param $seconds
     *
     * @return int
     */
    public function setExpires($seconds)
    {

        $this->expires = (int)$seconds;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {

        return $this->prefix;
    }

    /**
     * Sets the cache provider options
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options = array())
    {
        foreach ($options as $name => $value) {
            if (isset($this->$name)) {
                $this->$name = $value;

            }
        }
    }

    /**
     * @param mixed $prefix
     */
    public function setPrefix($prefix)
    {

        $this->prefix = $prefix;
    }
}
