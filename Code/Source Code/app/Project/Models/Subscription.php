<?php
/**
 * File: Subscription.php
 * Created: 03-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Project\Services\Cart\OrderItemRefundableInterface;
use Project\Services\Cart\OrderItemVoidableInterface;
use Project\Support\Orders\LinkableInterface;
use Project\Support\Subscriptions\Validator;
use Story\Auth;
use Story\Collection;
use Story\Error;
use Story\NotFoundException;
use Story\ORM;

/**
 * Class Subscription
 *
 * @property int id
 * @property     $status
 * @package Project\Models
 */
class Subscription extends ORM implements LinkableInterface, OrderItemVoidableInterface, OrderItemRefundableInterface
{
    /**
     * Not expired keyword
     */
    const ACTIVE = 'active';
    /**
     * All keyword
     */
    const ALL = 'all';
    /**
     * Disabled status keyword
     */
    const DISABLED = 'disabled';
    /**
     * Enabled status keyword
     */
    const ENABLED = 'enabled';
    /**
     * Expired keyword
     */
    const EXPIRED = 'expired';
    /**
     * Invoice prefix
     */
    const INVOICE_PREFIX = 'SUBSCR_';
    /**
     * Upcoming keyword
     */
    const UPCOMING = 'upcoming';
    /**
     * @var array
     */
    public static $belongs_to = array(
        'category' => '\Project\Models\SubscriptionCategory',
        'user'     => '\Project\Models\User',
        'order'    => '\Project\Models\Order',
    );
    /**
     * @var string
     */
    protected static $table = 'subscriptions';
    /**
     * Stores the user date
     *
     * @var mixed
     */
    public $user;

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
     * Creates a new subscription
     *
     * @param          $input
     * @param null|int $order_id
     *
     * @return Subscription|Validator
     */
    public static function createSubscription($input, $order_id = null)
    {

        $validator = Validator::create($input);
        if ($validator->validate()) {
            return static::build(
                $input,
                $order_id,
                null,
                isset($input['status']) ? (int)$input['status'] : 0
            );
        }

        return $validator;
    }

    /**
     * Create a new subscription for the user
     *
     * @param array                $input
     * @param null|int             $order_id
     * @param User                 $user
     * @param int                  $status
     * @param int                  $renewFrom
     *
     * @param SubscriptionCategory $category
     *
     * @return static
     */
    public static function build(
        array $input,
        $order_id = null,
        User $user = null,
        $status = 0,
        $renewFrom = null,
        SubscriptionCategory $category = null
    ) {

        try {
            // We get the category and copy the properties to the new subscription
            if (!$category) {
                /** @var SubscriptionCategory $category */
                $category = SubscriptionCategory::one(array('id' => $input['category'], 'status' => '1'));
                if (!$category) {
                    throw new NotFoundException('Subscription category not found [' . $input['category'] . ']');
                }
            }

            // and get the expires timestamp
            $expires = $category->getExpires($renewFrom)->timestamp;

            if (!$user) {
                // get the user also
                $user = User::findOrFail($input['user']);
            }

            // Now we build the subscription
            $attributes = array(
                'subscription_category_id' => $category->id,
                'user_id'                  => $user->id,
                'order_id'                 => $order_id,
                'status'                   => (int)$status,
                'description'              => isset($input['description']) ? $input['description'] : null,
                'starts'                   => $renewFrom ?: time(),
                'expires'                  => $expires,
            );
            foreach (array('name', 'interval', 'amount') as $attr_name) {
                $attributes[$attr_name] = $category->$attr_name;
            }

            $subscription = new static;
            $subscription->set($attributes);
            $subscription->save();

            $subscription->user = $user;

            if ($subscription->status) {
                event('subscription.' . ($renewFrom ? 'renewed' : 'created'), $subscription);
            } else {
                event('subscription.' . ($renewFrom ? 'renewed' : 'created') . '.inactive', $subscription);
            }

            return $subscription;
        } catch (\Exception $e) {
            Error::exception($e);
        }

        return false;
    }

    /**
     * Returns true if the passed subscription has expired
     *
     * @param \stdClass|Subscription $subscription
     *
     * @return bool
     */
    public static function expired($subscription)
    {

        if ($subscription instanceof \stdClass) {
            $model = new static;
            $model->set((array)$subscription);
        } else {
            $model = $subscription;
        }

        return !$model->isCurrent();
    }

    /**
     * Returns true if the subscription is the current one
     *
     * @return bool
     */
    public function isCurrent()
    {

        return $this->attributes['starts'] <= time() && $this->attributes['expires'] > time();
    }

    /**
     * Returns all the current subsciptions
     *
     * @param array $where
     *
     * @return $this
     */
    public static function getAllCurrent(array $where = array())
    {

        $where = self::whereCurrent($where);

        $collection = new Collection(
            static::fetch($where)
        );

        return $collection->load();
    }

    /**
     * Current where scope
     *
     * @param array $where
     *
     * @return array
     */
    protected static function whereCurrent(array &$where)
    {

        $where[] = static::$db->i('starts') . " <= '" . time() . "'";
        $where[] = static::$db->i('expires') . " > '" . time() . "'";

        return $where;
    }

    /**
     * Returns all the upcoming subsciptions
     *
     * @param array $where
     *
     * @return $this
     */
    public static function getAllUpcoming(array $where = null)
    {

        $where = self::whereUpcoming($where);

        $collection = new Collection(
            static::fetch($where)
        );

        return $collection->load();
    }

    /**
     * Upcoming where scope
     *
     * @param $where
     *
     * @return array
     */
    protected static function whereUpcoming(&$where)
    {

        $where[] = static::$db->i(static::$table . '.starts') . " >= '" . time() . "'";
        $where[] = static::$db->i(static::$table . '.expires') . " > '" . time() . "'";

        return $where;
    }

    /**
     * Checks if the user has a current subscription
     *
     * @param User $user
     *
     * @return int
     */
    public static function hasCurrent(User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        $where = array('user_id' => $user->id);
        static::whereCurrent($where);

        return static::count($where);
    }

    /**
     * Gets the previous subscriptions
     *
     * @param User  $user
     * @param array $where
     * @param int   $limit
     * @param int   $offset
     * @param array $order_by
     *
     * @return Collection
     */
    public static function getPrevious(
        User $user = null,
        array $where = null,
        $limit = 0,
        $offset = 0,
        array $order_by = array('expires' => 'desc')
    ) {

        if (!$user) {
            $user = Auth::user();
        }

        if (!$where) {
            $where = array();
        }

        $where['user_id'] = $user->id;
        static::whereExpired($where);

        $collection = new Collection(
            self::fetch(
                $where,
                $limit,
                $offset,
                $order_by
            )
        );

        $collection->load();

        return $collection;
    }

    /**
     * Expired where scope
     *
     * @param $where
     *
     * @return array
     */
    protected static function whereExpired(array &$where)
    {

        $where[] = static::$db->i(static::$table . '.starts') . " < '" . time() . "'";
        $where[] = static::$db->i(static::$table . '.expires') . " < '" . time() . "'";

        return $where;
    }

    /**
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     * @param string $status
     * @param string $expires
     *
     * @param string   $order
     *
     * @return array
     */
    public static function listSubscriptionsByQuery($query, $current, $per_page, $status, $expires, $order = null)
    {

        $tbl = static::$db->i(static::$table);
        $user_tbl = static::$db->i('users');
        $profile_tbl = static::$db->i('profiles');
        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}name{$i}");
        // do we have access to view the author? if so, we search them by author also

        $fields = array_merge($fields, array("{$user_tbl}.{$i}email{$i}", "{$profile_tbl}.{$i}value{$i}"));

        $queryWhere = query_to_where($query, $fields, '');

        return static::listSubscriptions($current, $per_page, $status, $expires, $queryWhere, $order);
    }

    /**
     * @param            $current
     * @param            $per_page
     * @param            $status
     * @param            $expires
     * @param null|array $queryWhere
     *
     * @param string     $order
     *
     * @return array
     */
    public static function listSubscriptions($current, $per_page, $status, $expires, $queryWhere = null, $order = null)
    {

        try {
            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$table);
            $user_tbl = static::$db->i(User::getTable());
            $profile_tbl = static::$db->i(Profile::getTable());

            $where = array();
            $params = array();

            // check if we need to select a status
            if ($status != static::ALL) {
                $where[] = $db->i($tbl . '.status') . " = ?";
                $params[] = ($status === static::ENABLED ? '1' : '0');
            }

            // check if we need a category
            if ($expires != static::ALL) {
                if ($expires === static::EXPIRED) {
                    self::whereExpired($where);
                } elseif ($expires === static::UPCOMING) {
                    self::whereUpcoming($where);
                } else {
                    static::whereCurrent($where);
                }
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

            // base query (using same for count also)
            $sql_base =
                // from
                "\n FROM {$tbl}"
                // join with users
                . "\n LEFT JOIN {$user_tbl} ON {$db->i($tbl . '.user_id')} = {$db->i($user_tbl . '.id')}"
                // join with the profiles
                . "\n LEFT JOIN {$profile_tbl} ON  {$db->i($tbl . '.user_id')} = {$db->i($profile_tbl . '.id')}"
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;

            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            $group_order_sql =
                // group by
                "\n GROUP BY {$db->i($tbl . '.id')}"
                // order
                . "\n ORDER BY " . ($order ?: "{$db->i($tbl . '.starts')} DESC");
            // build the query
            $sql = "SELECT {$tbl}.*, "
                // user data
                . "{$db->i($user_tbl .'.email')} as user_email, {$db->i($profile_tbl .'.value')} as user_name "
                . $sql_base
                . $group_order_sql
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
     * @param OrderItem $item
     *
     * @return OrderItem
     * @throws NotFoundException
     */
    public static function createFromOrderItem(OrderItem $item)
    {
        $item_data = $item->item_data;
        // we check if the item data is not already a subscription, if it is,
        // it means we already created the subscription
        if (!$item_data || $item_data instanceof Subscription) {
            return $item;
        }

        // get the user for this order
        $order = $item->order;
        /** @var OrderUser $user */
        $user = $order->order_user->first();
        $user->load();
        $user = User::findOrFail($user->user_id);


        // get the upcoming and current
        // get the current subscription
        $current = static::getCurrent($user);

        // get the upcoming
        $upcoming = static::getUpcoming($user);

        // do we have a current subscription? If yes, we check if we can renew it
        if ($current) {
            // We cannot renew it if the current cannot be renewed or there is an upcoming subscription
            if (!$current->canRenew() || $upcoming) {
                return $item;
            }
        }

        $starts = null;
        // When the user has no current, but has an upcoming, he buys the current
        if (!$current && $upcoming) {
        }

        if ($current && $current->canRenew() && !$upcoming) {
            $starts = $current->expires->timestamp;
        }

        // we cannot renew if current cannot be renewed or an upcoming exists
        if ($current && (!$current->canRenew() || $upcoming)) {
            return $item;
        }

        $model = static::build(array(), $order->id, $user, '1', $starts, $item_data);

        $item->orderable_id = $model->key();
        $item->orderable_type = get_class($model);
        $item->item_data = $model;

        $item->save();

        return $item;
    }

    /**
     * Returns the current subscription.
     *
     * @param User $user
     *
     * @return false|Subscription
     */
    public static function getCurrent(User $user = null)
    {

        if (!$user) {
            $user = Auth::user();
        }

        $where = array('user_id' => $user->id);
        static::whereCurrent($where);


        $items = (array)self::fetch($where, 1, 0, array('expires' => 'asc'));
        $item = current($items);

        if ($item) {
            $item->load();

            return $item;
        }

        return false;
    }

    /**
     * Returns the upcoming subscription
     *
     * @param User $user
     *
     * @return null|Subscription
     */
    public static function getUpcoming(User $user = null)
    {

        if (!$user) {
            $user = Auth::user();
        }

        $where = array('user_id' => $user->id);
        static::whereUpcoming($where);
        $items = (array)self::fetch($where, 1, 0, array('expires' => 'asc'));

        $item = current($items);

        if ($item) {
            $item->load();

            return $item;
        }

        return null;
    }

    /**
     * Returns true if the current subscription can be renewed (does not check permissions)
     *
     * @return bool
     */
    public function canRenew()
    {

        // We check if the subscription is current, if not cannot be renewed
        if (!$this->isCurrent()) {
            return false;
        }

        // We check if allowed to renew
        $allowRenewAfter = Carbon::instance($this->expires)->subDays(config('subscription_allow_renew_before', 30));

        return Carbon::now()->gt($allowRenewAfter);
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
     * Returns true if the current subscription can be renewed by admins (checks permissions)
     *
     * @param Subscription $upcomingSubscription
     *
     * @return bool
     */
    public function canAdminRenew(Subscription $upcomingSubscription = null)
    {

        return has_access('manage_admin_subscriptions_create') &&
        $this->isCurrent() && !$upcomingSubscription && $this->canRenew();
    }

    /**
     * Cancels the subscription and deletes the order if only initialized
     *
     * @return bool
     */
    public function cancel()
    {


        return $this->delete();
    }

    /**
     * Attempts to delete the subscription
     *
     * @param null $id
     *
     * @return bool
     */
    public function delete($id = null)
    {

        try {
            if ($this->status) {
                $item = clone $this;
                event('subscription.deleted', $item);
            }

            parent::delete($id);

            return true;
        } catch (\Exception $e) {

            Error::exception($e);
        }

        return false;
    }

    /**
     * Returns the days till the subscription expires
     *
     * @return int
     */
    public function daysTillExpire()
    {

        return Carbon::now()->diffInDays($this->expires);
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
     * Description accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getDescriptionAttribute($value)
    {

        return str_replace(
            array("\n"),
            array("<br>"),
            h($value)
        );
    }

    /**
     * Expires accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getExpiresAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }

    /**
     * Returns the expires mode (expired, upcoming, current)
     *
     * @return string
     */
    public function getExpiresMode()
    {

        if ($this->isCurrent()) {
            return static::ACTIVE;
        }

        if ($this->isUpcoming()) {
            return static::UPCOMING;
        }

        return static::EXPIRED;
    }

    /**
     * Returns true if the subscription is upcoming
     *
     * @return bool
     */
    public function isUpcoming()
    {

        return $this->attributes['starts'] >= time() && $this->attributes['expires'] > time();
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
     * Notifications accessor
     *
     * @param $value
     *
     * @return mixed
     */
    public function getNotificationsAttribute($value)
    {

        return json_decode($value, true);
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
     * Expires accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getStartsAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }

    /**
     * Notifications setter
     *
     * @param $value
     *
     * @return string
     */
    public function setNotificationsAttribute($value)
    {

        return $this->attributes['notifications'] = json_encode($value);
    }

    /**
     * Updates the subscription from user form
     *
     * @param array $input
     *
     * @return $this|Validator
     */
    public function updateFromForm(array $input)
    {

        $validator = Validator::update($input);

        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();

                // Now we build the subscription
                $attributes = array(
                    'status'      => isset($input['status']) ? (int)$input['status'] : 0,
                    'description' => $input['description'],
                );

                $this->set($attributes);
                $this->save();

                // if we are renewing the subscription
                if (isset($input['renew'])) {
                    $subscription = $this->adminRenew($validator->data());
                } else {
                    $subscription = $this;
                }

                static::$db->pdo->commit();

                if ($this->status) {
                    event('subscription.updated', $subscription);
                } else {
                    event('subscription.updated.inactive', $subscription);
                }

                return $subscription;
            } catch (\Exception $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);
            }
        }

        return $validator;
    }

    /**
     * Admin renew
     *
     * @param array $input
     *
     * @return Subscription
     * @throws NotFoundException
     */
    protected function adminRenew(array $input)
    {

        $user = User::findOrFail($this->user_id);
        $subscription = $this->buildRenew($input, null, $user, $input['status']);

        return $subscription;
    }

    /**
     * Create a renew subscription for the user (starts when the current expires)
     *
     * @param array $input
     * @param null  $order_id
     * @param User  $user
     * @param int   $status
     *
     * @return Subscription
     */
    public function buildRenew(array $input, $order_id = null, User $user = null, $status = 0)
    {

        $starts = $this->expires->timestamp;

        return static::build($input, $order_id, $user, $status, $starts);
    }

    /**
     * Returns the order type like: Issue, etc.
     * @return string
     */
    public function getOrderType()
    {
        return 'Subscription';
    }

    /**
     * Returns the order name
     *
     * @return string
     */
    public function getOrderName()
    {
        return $this->name;
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
        if (has_access('admin_subscriptions_show')) {
            return action('\Project\Controllers\Admin\Subscriptions\Show', array($this->key()));
        }

        return false;
    }

    /**
     * Refunds the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function refundOrderItem(OrderItem $item)
    {
        $item_data = $item->item_data;
        // check if the item_data is a subscription, if it is, we continue, otherwise nothing to do
        if (!$item_data instanceof Subscription) {
            return $item;
        }

        // we refund this model by simply deleting the subscription
        $id = $item->orderable_id;
        $item->orderable_id = $item_data->subscription_category_id;
        $item_data->delete($id);

        // restore the category
        $item->orderable_type = 'Project\Models\SubscriptionCategory';
        $item->item_data = SubscriptionCategory::one(array('id' => $item->orderable_id));


        $item->save();
        return $item;
    }

    /**
     * Refunds the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function voidOrderItem(OrderItem $item)
    {
        // we void this model by simply deleting the subscription,
        return $this->refundOrderItem($item);
    }
}
