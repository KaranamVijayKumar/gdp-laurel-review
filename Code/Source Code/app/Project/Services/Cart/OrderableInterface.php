<?php
/**
 * File: OrderableInterface.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Cart;

use StoryCart\CartItemRepository;

interface OrderableInterface extends \StoryCart\OrderableInterface
{
    /**
     * Returns the item price
     *
     * @return int|null
     */
    public function getPrice();

    /**
     * Returns the order type like: Issue, etc.
     * @return string
     */
    public function getOrderType();

    /**
     * Get the orderable name
     * @return string
     */
    public function getName();

    /**
     * Called when an item is removed from the cart
     *
     * @param CartItemRepository $model
     *
     * @return mixed
     */
    public function removeFromCart(CartItemRepository $model);
}
