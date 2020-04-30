<?php
/**
 * File: AbstractOrderEvent.php
 * Created: 14-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Project\Models\Order;
use Project\Models\Profile;
use Project\Models\Template;
use Story\View;
use StoryCart\CartRepository;

abstract class AbstractOrderEvent extends AbstractEventHandler
{
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var Profile
     */
    protected $email;

    /**
     * @var Profile
     */
    protected $name;

    /**
     * @var View
     */
    protected $item_view;

    /**
     * @var Template
     */
    protected $template;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {
        $this->order = func_get_arg(0);
        $this->order->load();
        $this->loadOrderUser();
        $this->setItemsView();
        $this->setTemplate();
    }

    /**
     * Loads the order user
     */
    protected function loadOrderUser()
    {
        $this->order->order_user->load();
        $default = new Profile(array('value' => ''));
        $this->email = $this->order->order_user->findBy('name', 'email', $default);
        $this->name = $this->order->order_user->findBy('name', 'name', $default);
    }

    /**
     * Sets the item view
     */
    protected function setItemsView()
    {
        // get the order items
        $this->item_view = new View('cart/email_checkout_items');
        $this->item_view->set($this->order->getItemListToCart());
    }

    /**
     * @return void
     */
    abstract protected function setTemplate();

    /**
     * If the order has an associated cart we remove that
     */
    protected function deleteOrderCart()
    {
        // delete the cart if we have one
        if ($this->order->cart_id) {
            $db = CartRepository::$db;
            CartRepository::$db->delete(
                "DELETE FROM {$db->i(CartRepository::getTable())} WHERE {$db->i('id')} = ?",
                array($this->order->cart_id)
            );
        }
    }

    /**
     * Builds the replacemenents
     *
     * @return array
     */
    protected function createReplacements()
    {
        $replacements = array(
            'name'     => $this->name->value,
            'date'     => $this->order->created->toDayDateTimeString(),
            'status'   => $this->order->order_status,
            'items'    => $this->item_view,
            'order_id' => $this->order->orderId(),
        );

        return $replacements;
    }
}
