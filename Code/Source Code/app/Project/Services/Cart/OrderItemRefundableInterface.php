<?php
/**
 * File: OrderItemRefundableInterface.php
 * Created: 14-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Cart;

use Project\Models\OrderItem;

interface OrderItemRefundableInterface
{
    /**
     * Refunds the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function refundOrderItem(OrderItem $item);
}
