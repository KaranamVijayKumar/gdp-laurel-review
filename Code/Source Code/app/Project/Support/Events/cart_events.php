<?php
/*!
 * cart_events.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

/*
|--------------------------------------------------------------------------
| Before the Cart is emptied
|--------------------------------------------------------------------------
|
|
*/

event(
    'storycart.flush',
    null,
    function (\StoryCart\Cart $cart) {
        /** @var \StoryCart\CartItemRepository $item */
        foreach ($cart->all() as $item) {
            $item->type_payload->removeFromCart($item);
        }
    }
);

/*
|--------------------------------------------------------------------------
| Before removing a cart item
|--------------------------------------------------------------------------
|
|
*/

event(
    'storycart.forget',
    null,
    function (\StoryCart\CartItemRepository $item) {
        $item->type_payload->removeFromCart($item);
    }
);
