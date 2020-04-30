<?php
/**
 * File: OrderUser.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\Error;
use Story\ORM;

/**
 * Class OrderUser
 *
 * @package Project\Models
 */
class OrderUser extends ORM
{
    /**
     * @var array
     */
    public static $belongs_to = array(
        'user'  => '\Project\Models\User',
        'order' => '\Project\Models\Order',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var array
     */
    public static $order_fields = array(
        'email',
        'name',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'country',
        'phone'
    );
    /**
     * @var string
     */
    protected static $table = 'order_user';

    /**
     * @param Order $order
     * @param User            $user
     *
     * @param bool            $save
     *
     * @return Collection
     */
    public static function createFromOrder(Order $order, User $user, $save = true)
    {
        try {

            static::$db->pdo->beginTransaction();

            $collection = new Collection;
            // Create the order user
            $collection->push(static::createRow($order, $user, 'email', $user->email, $save));

            // order user profile
            $profiles = $user->profiles->load();
            $default = new Profile();
            $default->set(array('value' => ''));
            foreach (static::$order_fields as $name) {
                if ($name === 'email') {
                    continue;
                }
                $collection->push(
                    static::createRow($order, $user, $name, $profiles->findBy('name', $name, $default)->value, $save)
                );
            }

            static::$db->pdo->commit();
            return $collection;

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }

    /**
     * Create an entry
     *
     * @param Order  $order
     * @param User   $user
     * @param string $name
     * @param string $value
     *
     * @param bool   $save
     *
     * @return $this
     */
    public static function createRow(Order $order, User $user, $name, $value, $save = true)
    {
        $order_user = new OrderUser;
        $order_user->set(
            array(
                'order_id' => $order->key(),
                'user_id'  => $user->key(),
                'name'     => $name,
                'value'    => $value
            )
        );

        if ($save) {
            return $order_user->save();
        }

        return $order_user;
    }
}
