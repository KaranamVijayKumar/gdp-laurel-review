<?php
/**
 * File: subscription_helpers.php
 * Created: 25-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

use Project\Models\SubscriptionCategory;

/**
 * Returns the currently enabled subscription categories
 *
 * @param array $where
 *
 * @return array
 */
function subscription_categories(array $where = array('status' => '1'))
{

    static $categories = false;

    if ($categories !== false) {
        return $categories;
    }
    return $categories = SubscriptionCategory::lists(
        'id',
        'name',
        $where,
        0,
        0,
        array('name' => 'asc')
    );
}
