<?php
/**
 * File: OrderItemCollection.php
 * Created: 06-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Orders;

use Story\Collection;

/**
 * Class OrderItemCollection
 * @package Project\Support\Orders
 */
class OrderItemCollection extends Collection
{
    /**
     * Returns the items that needs shipping
     */
    public function getShippableItems()
    {
        $ship_items = array();

        foreach ($this->items as $item) {
            $model = $item->item_data;

            if ($model instanceof ShippableInterface) {
                $ship_items[] = $item;
            }
        }

        return new static($ship_items);

    }
}
