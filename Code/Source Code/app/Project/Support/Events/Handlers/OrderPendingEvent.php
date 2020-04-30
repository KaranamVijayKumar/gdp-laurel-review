<?php
/**
 * File: OrderPendingEvent.php
 * Created: 14-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Project\Models\Order;

/**
 * Class OrderPendingEvent
 * @package Project\Support\Events\Handlers
 */
class OrderPendingEvent extends AbstractEventHandler
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {
        $this->order = func_get_arg(0);
    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        $this->order->logStatusChange();
    }
}
