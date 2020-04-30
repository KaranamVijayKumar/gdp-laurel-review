<?php
/**
 * File: Order.php
 * Created: 12-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Project\Services\Billing\PaymentEventInterface;
use Project\Services\Cart\OrderableInterface;
use Project\Services\Cart\OrderItemProcessableInterface;
use Project\Services\Cart\OrderItemRefundableInterface;
use Project\Services\Cart\OrderItemVoidableInterface;
use Project\Services\Logs\LoggableInterface;
use Project\Support\LogFactory;
use Story\Auth;
use Story\Collection;
use Story\Error;
use Story\ORM;
use StoryCart\OrderRepository;

/**
 * Class Order
 * @package Project\Models
 */
class Order extends OrderRepository implements LoggableInterface, PaymentEventInterface
{
    /**
     * Invoice prefix
     */
    const INVOICE_PREFIX = 'TLR_O_';

    /**
     * All statuses
     */
    const STATUS_ALL = 'All';

//    /**
//     * Canceled order
//     */
//    const STATUS_CANCELED = 'Canceled';
//
//    /**
//     * Canceled reversal
//     */
//    const STATUS_CANCELED_REVERSAL = 'Canceled Reversal';

//    /**
//     * Complete order
//     */
//    const STATUS_COMPLETE = 'Complete';

//    /**
//     * Denied order
//     */
//    const STATUS_DENIED = 'Denied';

//    /**
//     * Failed order
//     */
//    const STATUS_FAILED = 'Failed';

//    /**
//     * Initialized order
//     */
//    const STATUS_INITIALIZED = 'Initialized';

    /**
     * Pending order
     */
    const STATUS_PENDING = 'Pending';

    /**
     * Processed order
     */
    const STATUS_PROCESSED = 'Processed';

//    /**
//     * Processing order
//     */
//    const STATUS_PROCESSING = 'Processing';

    /**
     * Refunded order
     */
    const STATUS_REFUNDED = 'Refunded';

    /**
     * Shipped order
     */
    const STATUS_SHIPPED = 'Shipped';

    /**
     * Void order
     */
    const STATUS_VOIDED = 'Voided';

    /**
     * Has many relations
     *
     * @var array
     */
    public static $has_many = array(
        'order_user' => 'Project\Models\OrderUser',
        'items'      => 'Project\Models\OrderItem',
    );

    /**
     * Foreign key name
     *
     * @var string
     */
    protected static $foreign_key = 'order_id';

    /**
     * Table name
     *
     * @var string
     */
    protected static $table = 'orders';

    /**
     * Logs
     * @var array|null|Collection
     */
    public $logs = null;

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {
        require_once SP . '/Project/Support/Events/order_events.php';

        return parent::__construct($id);
    }

    /**
     * Deletes an order by id and status
     *
     * @param        $id
     * @param string $status
     *
     * @return int
     */
    public static function deleteByIdAndStatus($id, $status = Order::STATUS_PENDING)
    {
        if ($id) {
            $db = Order::$db;

            return $db->delete(
                "DELETE FROM {$db->i(Order::getTable())} WHERE {$db->i('id')} = ? AND {$db->i('order_status')} = ?",
                array($id, $status)
            );
        }

        return false;
    }

    /**
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     *
     * @param        $status
     *
     * @param string   $order
     *
     * @return array
     */
    public static function listOrdersByQuery($query, $current, $per_page, $status, $order = null)
    {

        $tbl = static::$db->i(static::$table);
        $tbl_user = static::$db->i(OrderUser::getTable());
        $i = static::$db->i;

        // remove invoice prefix
        $query = str_replace(
            array(
                static::INVOICE_PREFIX,
                mb_convert_case(static::INVOICE_PREFIX, MB_CASE_LOWER)
            ),
            '',
            $query
        );

        $fields = array(
            "{$tbl}.{$i}id{$i}",
            "{$tbl_user}.{$i}user_name{$i}",
            "{$tbl_user}.{$i}user_email{$i}"
        );

        $queryWhere = query_to_where($query, $fields, '');

        return static::listOrders($current, $per_page, $status, $queryWhere, $order);
    }

    /**
     * @param            $current
     * @param            $per_page
     * @param            $status
     * @param null|array $queryWhere
     *
     * @param string       $order
     *
     * @return array
     */
    public static function listOrders($current, $per_page, $status, $queryWhere = null, $order = null)
    {
        try {
            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$table);
            $tbl_user = static::$db->i(OrderUser::getTable());

            $where = array();
            $params = array();

            // check if we need to select a status
            if (array_key_exists($status, static::getOrderStatusList())) {
                $where[] = $db->i($tbl . '.order_status') . " = ?";
                $params[] = $status;
            }
            // check if we have query, and include that in the sql
            $query = '';
            if ($queryWhere) {

                $params = array_merge($params, $queryWhere['values']);

                if ($where) {
                    $query .= ' AND ';
                } else {
                    $query .= ' WHERE ';
                }

                $query .= "(" . $queryWhere['sql'] . ")";
            }

            $sql_base =
                // from
                "\n FROM {$tbl}"
                // join
                . "\n LEFT JOIN ("
                . "\n\t SELECT \n\t\t{$db->i($tbl_user.'.order_id')},"
                . "\n\t\t MAX(IF({$db->i('name')} = 'name', {$db->i('value')}, NULL)) AS {$db->i('user_name')},"
                . "\n\t\t MAX(IF({$db->i('name')} = 'email', {$db->i('value')}, NULL)) AS {$db->i('user_email')}"
                . "\n\t FROM {$tbl_user}"
                . "\n\t GROUP BY {$db->i($tbl_user.'.order_id')}"
                . "\n) {$tbl_user} ON {$db->i($tbl_user . '.order_id')} = {$db->i($tbl.'.id')}"
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;


            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            // build the query
            $sql = "SELECT {$tbl}.*, {$db->i($tbl_user . '.user_name')}, {$db->i($tbl_user . '.user_email')} "
                // user data
                . $sql_base
                . "\n ORDER BY " . ($order ? : "{$db->i($tbl . '.created')} DESC")
                . $sql_limit;

            // execute the query
            $items = static::$db->fetch($sql, $params);
            foreach ($items as $id => $row) {
                $items[$id] = new static($row);
            }
            $items = new Collection($items);

            // count sql
            $count_sql = "SELECT COUNT(DISTINCT {$db->i($tbl .'.id')})"
                . "\n" . $sql_base;

            $count = static::$db->column($count_sql, $params);

            // commit
            static::$db->pdo->commit();

            return array('total' => $count, 'items' => $items);
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Returns the order status list
     *
     * @return array
     */
    public static function getOrderStatusList()
    {
        $array = array(
            Order::STATUS_PENDING           => _(Order::STATUS_PENDING),
            Order::STATUS_PROCESSED         => _(Order::STATUS_PROCESSED),
            Order::STATUS_SHIPPED           => _(Order::STATUS_SHIPPED),
            Order::STATUS_COMPLETE          => _(Order::STATUS_COMPLETE),
            Order::STATUS_REFUNDED          => _(Order::STATUS_REFUNDED),
            Order::STATUS_VOIDED            => _(Order::STATUS_VOIDED),
//            Order::STATUS_CANCELED          => _(Order::STATUS_CANCELED),
//            Order::STATUS_CANCELED_REVERSAL => _(Order::STATUS_CANCELED_REVERSAL),
//            Order::STATUS_COMPLETE          => _(Order::STATUS_COMPLETE),
//            Order::STATUS_FAILED            => _(Order::STATUS_FAILED),
//            Order::STATUS_PROCESSING        => _(Order::STATUS_PROCESSING),
//            Order::STATUS_INITIALIZED       => _(Order::STATUS_INITIALIZED)
        );


        return $array;
    }

    /**
     * This function is called when a payment is related to a model
     *
     * @param int    $id
     * @param string $status
     *
     * @return bool
     */
    public static function payEvent($id, $status)
    {

        /** @var \Project\Services\Billing\Paypal\Payment $payment */
        $payment = app('container')->make('\Project\Services\Billing\PaymentInterface');

        // we update the submission based on the status
        /** @var Order $model */
        $model = static::findOrFail($id);

        $model->items->load();

        require_once SP . '/Project/Support/Events/order_events.php';

        switch ($status) {

            case $payment::PAYMENT_STATUS_PENDING:
            case $payment::PAYMENT_STATUS_CREATED:
            case $payment::PAYMENT_STATUS_PROCESSED:

                // set status to pending
                $model->order_status = static::STATUS_PENDING;
                $model->save();

                event('order.pending', $model);
                return true;
                break;
            case $payment::PAYMENT_STATUS_COMPLETED:
            case $payment::PAYMENT_STATUS_CANCELED_REVERSAL:

                // set status to processed if the order has shippable items
                // otherwise set to completed
                if (count($model->items->getShippableItems())) {

                    $model->order_status = static::STATUS_PROCESSED;
                    $model->save();
                    event('order.processed', $model);

                } else {
                    $model->order_status = static::STATUS_COMPLETE;
                    $model->save();
                    event('order.complete', $model);
                }
                return true;
                break;

            case $payment::PAYMENT_STATUS_REFUNDED:
                // order refunded email
                $model->order_status = static::STATUS_REFUNDED;
                $model->save();
                event('order.refunded', $model);
                return true;
                break;

            case $payment::PAYMENT_STATUS_DENIED:
            case $payment::PAYMENT_STATUS_EXPIRED:
            case $payment::PAYMENT_STATUS_FAILED:
            case $payment::PAYMENT_STATUS_REVERSED:
            case $payment::PAYMENT_STATUS_VOIDED:

                $model->submission_status_id = static::STATUS_VOIDED;
                $model->save();
                event('order.voided', $model);
                return true;
                break;


        }

        return false;
    }

    /**
     * Processes the order items (might create submissions, subscriptions, etc!)
     */
    public function processItems()
    {
        try {
            ini_set('memory_limit', '384M');
            ini_set('max_execution_time', 300);
            set_time_limit(300);

            static::$db->pdo->beginTransaction();

            // we execute for each order item the processOrderItem
            foreach ($this->items->load() as $item) {
                $item_data = $item->item_data;

                if ($item_data instanceof OrderItemProcessableInterface) {
                    $item_data->processOrderItem($item);
                }
            }

            static::$db->pdo->commit();
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }
    }

    /**
     * Issues refunds to order items by disabling them
     */
    public function refundItems()
    {
        try {
            ini_set('memory_limit', '384M');
            ini_set('max_execution_time', 300);
            set_time_limit(300);

            static::$db->pdo->beginTransaction();

            // we execute for each order item the processOrderItem
            foreach ($this->items->load() as $item) {
                $item_data = $item->item_data;

                if ($item_data instanceof OrderItemRefundableInterface) {
                    $item_data->refundOrderItem($item);
                }
            }

            static::$db->pdo->commit();
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }
    }

    /**
     * Issues refunds to order items by disabling them
     */
    public function voidItems()
    {
        try {
            ini_set('memory_limit', '384M');
            ini_set('max_execution_time', 300);
            set_time_limit(300);

            static::$db->pdo->beginTransaction();

            // we execute for each order item the processOrderItem
            foreach ($this->items->load() as $item) {
                $item_data = $item->item_data;

                if ($item_data instanceof OrderItemVoidableInterface) {
                    $item_data->voidOrderItem($item);
                }
            }

            static::$db->pdo->commit();
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }
    }

    /**
     * Updates the order from form
     *
     * @param $data
     *
     * @return $this|bool
     */
    public function updateFromForm($data)
    {

        $this->order_status = $data['status'];

        if ($this->save()) {

            event('order.' . slug($this->order_status, '_'), $this);

            return $this;
        }

        return false;
    }

    /**
     * Logs the status change
     */
    public function logStatusChange()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->profiles->load();
            $name = $user->profiles->findBy('name', 'name', '')->value;
            // log the action
            Log::create(
                $this,
                'Order {id} status changed to "' . $this->order_status . '" by {user}',
                array(
                    'id'            => $this->id,
                    'id_fallback'   => $this->orderId(),
                    'user'          => $user->id,
                    'user_fallback' => $name
                )
            );
        } else {
            // log the action
            Log::create(
                $this,
                'Order {id} status changed to "' . $this->order_status . '"',
                array(
                    'id'          => $this->id,
                    'id_fallback' => $this->orderId(),
                )
            );
        }
    }

    /**
     * Returns the order id
     *
     * @return string
     */
    public function orderId()
    {
        return static::generateOrderId($this->id);
    }

    /**
     * Generates an order id
     *
     * @param string $order_id
     *
     * @return string
     */
    public static function generateOrderId($order_id)
    {
        return static::INVOICE_PREFIX . $order_id;
    }

    /**
     * @return Collection
     */
    public function getHistory()
    {
        // get the logs
        $this->getLogs();

        // get the payment history
        $this->getPaymentHistory();

        // we merge the logs and payment history and sort them based on creation date
        $all = array_merge($this->logs->all(), $this->payments->all());

        usort($all, function ($a, $b) {
            if ($a->created == $b->created) {
                return 0;
            }

            return ($b->created < $a->created) ? -1 : 1;
        });

        return $this->history = new Collection($all);
    }

    /**
     * Load and returns the logs
     *
     * @return Collection
     */
    public function getLogs()
    {
        if ($this->logs !== null) {
            return $this->logs;
        }

        return $this->logs = LogFactory::model(
            $this,
            function ($log) {
                /** @var Order $model */
                $model = new $log->loggable_type;
                $model->set(array('id' => $log->loggable_id));

                return '<strong>' . $model->orderId() . '</strong>';
            },
            0,
            0,
            array('created' => 'desc')
        );
    }

    /**
     * Returns the payment history
     *
     * @return Collection
     */
    public function getPaymentHistory()
    {
        return $this->payments = Payment::many($this);
    }

    /**
     * Created accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getCreatedAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }

    /**
     * Modified accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getModifiedAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }

    /**
     * Assigns the current user to the order
     *
     * @param array $data
     *
     * @return Collection
     */
    public function assignCurrentUser(array $data)
    {
        // if the user is signed in, we use that user, otherwise an empty user

        if (Auth::check()) {
            $user = Auth::user();
            $user->profiles->load();
            $user = clone $user;
        } else {
            $user = new User;
            $user->profiles = new Collection;
        }

        // update the email
        if (isset($data['email'])) {
            $user->set(array('email' => $data['email']));
        }


        // then the rest of the data
        $default = new Profile(array('value' => ''));
        foreach (OrderUser::$order_fields as $name) {
            if ($name === 'email') {
                continue;
            }
            $profile = $user->profiles->findBy('name', $name);
            if (!$profile) {
                $profile = clone $default;
                $user->profiles->push($profile);
            }

            $profile->name = $name;
            if (isset($data[$name])) {
                $profile->value = $data[$name];
            }
        }

        return OrderUser::createFromOrder($this, $user, true);
    }

    /**
     * Returns the item list that is in the current order along with the order totals
     *
     * @return array
     */
    public function getItemListToCart()
    {

        // get the order items
        $order_items = $this->items->load();

        $items = array();
        foreach ($order_items as $item) {

            /** @var OrderableInterface $model */
            $model = $item->item_data;

            $items[] = (object)array(
                'quantity' => $item->quantity,
                'amount'   => $item->price,
                'tax'      => $item->tax,
                'type'     => $model->getOrderType(),
                'currency' => $item->currency,
                'name'     => $model->getName()
            );
        }

        $sub_total = $this->sub_total;
        $tax = $this->tax;
        $order_total = $this->order_total;
        $currency = $this->currency;

        return compact('items', 'sub_total', 'tax', 'order_total', 'currency');
    }
}
