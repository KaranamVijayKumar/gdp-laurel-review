<?php
/**
 * File: Edit.php
 * Created: 04-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Orders;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Log;
use Project\Models\Order;
use Project\Models\User;
use Project\Support\Orders\ShippableInterface;
use Project\Support\Orders\Validator;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Edit
 * @package Project\Controllers\Admin\Orders
 */
class Edit extends AdminBaseController
{

    /**
     * @var Order
     */
    public $order;

    /**
     * User who placed the order
     *
     * @var User
     */
    public $order_user;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $template = 'admin/orders/edit';

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('orders');

    /**
     * Order view
     *
     * @param $id
     */
    public function get($id)
    {
        try {

            $this->order = Order::findOrFail((int)$id);

            // load the user for the model
            $this->order->order_user->load();

            // and the purchased items also
            $this->order->items->load();

            // If the user has access to user data, we include that also
            if (has_access('admin_users_edit') && count($this->order->order_user)) {
                $this->order_user = User::find($this->order->order_user->first()->user_id);
            }

            // get the log and payment history
            $this->order->getHistory();

            // set the title
            $this->title = _('Order');
        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Orders\Index'),
                array(
                    'error' => _('Order not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Updates the order
     *
     * @param $id
     */
    public function post($id)
    {
        try {

            $this->order = Order::findOrFail((int)$id);

            // validate and update the order
            $v = new Validator($_POST);

            if ($v->validate() && ($this->order->updateFromForm($v->data())) instanceof Order) {
                redirect(
                    action('\Project\Controllers\Admin\Orders\Edit', array($this->order->id)),
                    array(
                        'notice' => _('Saved.')
                    )
                );
            }

            redirect(
                action('\Project\Controllers\Admin\Orders\Edit', array($this->order->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Orders\Index'),
                array(
                    'error' => _('Order not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
