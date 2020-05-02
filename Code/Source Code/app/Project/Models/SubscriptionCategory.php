<?php
/**
 * File: SubscriptionCategory.php
 * Created: 03-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Exception;
use Project\Services\Cart\OrderableInterface;
use Project\Services\Cart\OrderItemProcessableInterface;
use Project\Support\Orders\LinkableInterface;
use Project\Support\Subscriptions\CategoryValidator;
use Story\Collection;
use Story\Error;
use Story\ORM;
use StoryCart\CartItemRepository;

/**
 * Class SubscriptionCategory
 *
 * @package Project\Models
 */
class SubscriptionCategory extends ORM implements OrderableInterface, LinkableInterface, OrderItemProcessableInterface
{
    /**
     * Are we calculating the calendaristic year?
     */
    const CALENDARISTIC = false;

    /**
     * Valid interval values
     *
     * @var array
     */
    public static $intervals = array('12', '24');

    /**
     * Foreign key name
     *
     * @var string
     */
    protected static $foreign_key = 'subscription_category_id';

    /**
     * @var string
     */
    protected static $table = 'subscription_categories';

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {

        require_once SP . '/Project/Support/Events/subscription_events.php';
        return parent::__construct($id);
    }

    /**
     * Creates a new subscription category from form
     *
     * @param array $input
     *
     * @return bool|CategoryValidator
     */
    public static function createFromForm(array $input)
    {

        $validator = CategoryValidator::create($input);

        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();

                $category = new static;
                $category->set(
                    array(
                        'name'     => $input['name'],
                        'interval' => $input['interval'],
                        'amount'   => $input['amount'],
                        'status'   => isset($input['status']) ? (int)$input['status'] : 0
                    )
                );
                $category->save();
                static::$db->pdo->commit();
                event('subscription.category.created', $category);
                return true;
            } catch (Exception $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);
            }
        }

        return $validator;
    }

    /**
     * Deletes a category
     *
     * @param $id
     *
     * @return bool
     */
    public static function destroy($id)
    {

        try {
            static::$db->pdo->beginTransaction();
            $to_delete = static::findOrFail($id);
            $item = clone $to_delete;
            $to_delete->delete();

            event('subscription.category.deleted', $item);
            static::$db->pdo->commit();
            return true;

        } catch (\Exception $e) {
            SubscriptionCategory::$db->pdo->rollBack();
            // redirect back to role list
            redirect(action('\Project\Controllers\Admin\Subscriptions\Categories'), array('error' => $e->getMessage()));
        }
        return false;
    }

    /**
     * Returns the intervals
     *
     * @return array
     */
    public static function getIntervals()
    {
        $intervals = array();
        foreach (static::$intervals as $interval) {
            $intervals[$interval] = sprintf(ngettext('%s Month', '%s Months', $interval), $interval);
        }
        return $intervals;
    }

    /**
     * Lists the categories
     *
     * @param $current
     * @param $per_page
     *
     * @return array
     */
    public static function listCategories($current, $per_page)
    {

        try {

            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current ? $current - 1 : 1);

            $tbl = static::$db->i(static::$table);
            $sql = "SELECT * FROM $tbl ORDER BY " . static::$db->i('name') . " ASC " .
                "LIMIT $per_page OFFSET $offset";
            $items = new Collection(static::$db->fetch($sql));

            // get count
            $count = static::$db->select('COUNT(*)', static::$table);
            $count = static::$db->column($count[0], $count[1]);

            static::countAllSubscriptions($items);

            static::$db->pdo->commit();


            return array('total' => $count, 'items' => $items);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Counts all the subscriptions for the passed categories
     *
     * @param Collection $items
     */
    private static function countAllSubscriptions(Collection $items)
    {

        if (!count($items)) {
            return;
        }

        $ids = $items->lists();
        $idPlaceholders = trim(str_repeat('?,', count($ids)), ',');
        $i = static::$db->i;

        $sql = ("SELECT {$i}subscription_category_id{$i}, COUNT(*) as {$i}subscriptionCount{$i} " .
            "FROM {$i}subscriptions{$i} WHERE {$i}subscription_category_id{$i} IN ({$idPlaceholders}) " .
            "GROUP BY {$i}subscription_category_id{$i}");

        $subscriptionCounts = new Collection(static::$db->fetch($sql, $ids));

        foreach ($subscriptionCounts as $subscriptionCount) {
            $item = $items->findBy('id', $subscriptionCount->subscription_category_id);
            if (!isset($item->subscriptionCount)) {
                $item->subscriptionCount = 0;
            }
            $item->subscriptionCount = $subscriptionCount->subscriptionCount;
        }

    }

    /**
     * Lists the categories by search query
     *
     * @param $query
     * @param $current
     * @param $per_page
     *
     * @return array
     */
    public static function listCategoriesByQuery($query, $current, $per_page)
    {

        try {

            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current ? $current - 1 : 1);

            $tbl = static::$db->i(static::$table);

            $where = query_to_where(
                $query,
                array('name', 'amount', 'description')
            );


            $sql = "SELECT * FROM $tbl WHERE {$where['sql']} ORDER BY " . static::$db->i('name') . " ASC " .
                "LIMIT $per_page OFFSET $offset";
            $items = new Collection(static::$db->fetch($sql, $where['values']));

            // get count
            $sql = "SELECT COUNT(*) as c FROM {$tbl} WHERE {$where['sql']}";
            /** @var \stdClass $count */
            $count = static::$db->row($sql, $where['values']);
            $count = $count->c;

            static::countAllSubscriptions($items);

            static::$db->pdo->commit();

            return array('total' => $count, 'items' => $items);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Returns the actual expires timestamp
     *
     * @param int $timestamp
     *
     * @return Carbon /Carbon;
     */
    public function getExpires($timestamp = null)
    {

        // If calendaristic year, we calculate the current year.
        // If the subscription is created after dec. 1, the subscription will start from jan 1 next year
        if ($timestamp) {
            $date = Carbon::createFromTimestamp($timestamp, config('timezone'));
        } else {
            $date = Carbon::now(config('timezone'));

        }
        if (static::CALENDARISTIC) {
            if ($date->month == 12) {
                $date = Carbon::create($date->year + 1, 1, 1, 0, 0, 0, config('timezone'));
            } else {
                $date = Carbon::create($date->year, 1, 1, 0, 0, 0, config('timezone'));
            }
        }

        // Add the interval to the current date and subtract 1 second
        // might need to round up to 23:59:59 (use ->endOfDay())

        $date->addMonths($this->interval)->endOfDay();
        return $date;
    }

    /**
     * @param array $input
     *
     * @return bool|CategoryValidator
     */
    public function updateFromForm(array $input)
    {

        $validator = CategoryValidator::edit($input, $this);

        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();

                $this->set(
                    array(
                        'name'     => $input['name'],
                        'interval' => $input['interval'],
                        'amount'   => $input['amount'],
                        'status'   => isset($input['status']) ? (int)$input['status'] : 0
                    )
                );

                $this->save();
                static::$db->pdo->commit();
                event('subscription.category.updated', $this);
                return true;
            } catch (\Exception $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);
            }
        }

        return $validator;
    }

    /**
     * Creates the cart item model from the cart item
     *
     * @param \StoryCart\CartItemRepository $item
     *
     * @return \StoryCart\OrderItemRepository
     */
    public static function createFromCart(CartItemRepository $item)
    {
        $model = new OrderItem;

        $model->set(
            array(
                'orderable_id' => $item->type_id,
                'orderable_type' => $item->type,
                'item_data' => $item->type_payload,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax' => $item->tax,
                'currency' => $item->currency,

            )
        );

        return $model;
    }

    /**
     * Returns the order assets
     *
     * @return mixed
     */
    public function getCartAssets()
    {
        return null;
    }

    /**
     * Returns the inventory
     *
     * @param int $id
     *
     * @return int
     */
    public static function getInventory($id)
    {
        return 2;
    }

    /**
     * Get the orderable name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the order payload
     *
     * @return mixed
     */
    public function getCartPayload()
    {
        return $this;
    }

    /**
     * Returns the item price
     *
     * @return int|null
     */
    public function getPrice()
    {
        return $this->amount;
    }

    /**
     * Returns the order type like: Issue, etc.
     * @return string
     */
    public function getOrderType()
    {
        return _('Subscription');
    }

    /**
     * Called when an item is removed from the cart
     *
     * @param CartItemRepository $model
     *
     * @return mixed
     */
    public function removeFromCart(CartItemRepository $model)
    {
        return null;
    }

    /**
     * @return boolean
     */
    public function canLink()
    {
        return is_numeric($this->key());
    }

    /**
     * @return string
     */
    public function getAdminLink()
    {
        if (has_access('admin_subscriptions_categories_edit')) {
            return action('\Project\Controllers\Admin\Subscriptions\Categories@edit', array($this->key()));
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return false;
    }


    /**
     * Processes the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function processOrderItem(OrderItem $item)
    {
        // since we are create a subscription, we delegate the subscription creation to that model
        Subscription::createFromOrderItem($item);

        return $item;
    }
}
