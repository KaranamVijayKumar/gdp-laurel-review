<?php
/**
 * File: OrderItemProcessableInterface.php
 * Created: 13-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Cart;

use Project\Models\OrderItem;

/**
 * Interface OrderItemProcessableInterface
 * @package Project\Services\Cart
 */
interface OrderItemProcessableInterface
{
    /**
     * Processes the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function processOrderItem(OrderItem $item);
}
