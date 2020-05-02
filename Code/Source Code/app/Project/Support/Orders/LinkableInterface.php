<?php
/**
 * File: LinkableInterface.php
 * Created: 12-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Orders;

/**
 * Interface LinkableInterface
 * @package Project\Support\Orders
 */
interface LinkableInterface
{
    /**
     * @return boolean
     */
    public function canLink();

    /**
     * @return string
     */
    public function getAdminLink();
}
