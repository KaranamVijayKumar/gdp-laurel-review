<?php
/**
 * File: PrivateAssets.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class PrivateAsset
 *
 * @package Project\Models
 */
class PrivateAsset extends ORM
{

    /**
     * @var string
     */
    protected static $table = 'private_assets';
}
