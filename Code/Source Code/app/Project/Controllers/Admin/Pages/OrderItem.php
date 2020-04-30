<?php
/**
 * File: OrderItem.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Services\Cart\OrderableInterface;
use Project\Support\Orders\OrderItemCollection;
use Story\Collection;
use Story\ORM;
use StoryCart\OrderItemRepository;

class OrderItem extends OrderItemRepository
{
    /**
     * @var array
     */
    public static $belongs_to = array(
        'order' => '\Project\Models\Order',
    );

    /**
     * Creates a new order item
     *
     * @param Order              $order
     * @param OrderableInterface $model
     */
    public static function createItem(Order $order, OrderableInterface $model)
    {
        // order item
        $order_item = new static;
        /** @noinspection PhpUndefinedFieldInspection */
        $order_item->set(
            array(
                'order_id'       => $order->id,
                'orderable_id'   => $model->id,
                'orderable_type' => get_class($model),
                'quantity'       => 1,
                'price'          => round($model->getPrice(), 2),
                'tax'            => config('issue_tax', 0),
                'currency'       => 'USD',
                'item_data'      => $model
            )
        );
        $order_item->save();
    }

    /**
     * Defines the collection
     *
     * @param $items
     *
     * @return Collection
     */
    public static function collection($items)
    {
        return new OrderItemCollection($items);
    }
}
