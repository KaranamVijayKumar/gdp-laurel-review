<?php
/**
 * File: Profile.php
 * Created: 27-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class Profile
 *
 * @package Project\Models
 */
class Profile extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'user' => '\Project\Models\User',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var array
     */
    protected static $protected = array('id', 'user_id');

    /**
     * @var string
     */
    protected static $table = 'profiles';
}
