<?php
/**
 * File: PageData.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class PageData
 *
 * @package Project\Models
 */
class PageData extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'page' => '\Project\Models\Page',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'page_data';

    /**
     * Value accessor
     *
     * @param $value
     *
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * Value mutator
     *
     * @param $value
     *
     * @return string
     */
    public function setValueAttribute($value)
    {

        return $this->attributes['value'] = serialize($value);
    }
}
