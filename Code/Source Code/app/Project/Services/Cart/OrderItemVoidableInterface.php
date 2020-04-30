<?php
/**
 * File: OrderItemVoidableInterface.php
 * Created: 14-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Cart;

use Project\Models\OrderItem;

/**
 * Interface OrderItemVoidableInterface
 * @package Project\Services\Cart
 */
interface OrderItemVoidableInterface
{
    /**
     * Refunds the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function voidOrderItem(OrderItem $item);
}
