<?php
/**
 * File: Index.php
 * Created: 04-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Orders;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Order;
use Project\Models\UserData;
use Story\Pagination;
use Story\View;

/**
 * Class Index
 * @package Project\Controllers\Admin\Orders
 */
class Index extends AdminBaseController
{

    /**
     * @var string
     */
    public $title;

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('orders');

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/orders/index';

    /**
     * @var string
     */
    public $query;

    /**
     * @var Pagination
     */
    public $pagination;

    /**
     * @var \Story\Collection
     */
    public $items;

    /**
     * @var int
     */
    public $total;

    /**
     * @var string
     */
    public $selectedOrderStatus;

    /**
     * @var array
     */
    public $statuses;

    /**
     * @var int
     */
    public $current_page = 1;

    /**
     * Lists the orders
     * @param null $status
     * @throws \Exception
     */
    public function get($status = null)
    {
        $this->title = _('Orders');

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);
        $this->current_page = (int)get('page', 1);

        $this->selectedOrderStatus = $status ?: Order::STATUS_ALL;

        UserData::redirectToFilter(
            'orders.filter',
            array('status' => $this->selectedOrderStatus),
            array('status' => Order::STATUS_ALL),
            '\Project\Controllers\Admin\Orders\Index'
        );
        $this->setActionSelects();

        // no query, we list submissions by name
        if (!$this->query) {
            $items = Order::listOrders(
                $this->current_page,
                config('per_page'),
                $this->selectedOrderStatus
            );
        } else {
            // we have query, get the submissions filtered
            $items = Order::listOrdersByQuery(
                $this->query,
                $this->current_page,
                config('per_page'),
                $this->selectedOrderStatus
            );
        }

        $this->pagination = new Pagination($items['total'], $this->current_page, config('per_page'));

        $this->items = $items['items'];
        $this->total = $items['total'];

        if ($items['total'] <= config('per_page')) {
            $this->pagination = '';
        }

        // if json is requested we send the json items only
        if (AJAX_REQUEST) {
            $layout = new View('admin/orders/items.partial');
            foreach (array('items', 'pagination', 'total', 'selectedOrderStatus', 'query', 'current_page') as $name) {
                $layout->$name = $this->$name;
            }
            $this->json = array('items' => (string)$layout);
        }
    }

    /**
     * Sets the action select dropdown arrays
     */
    protected function setActionSelects()
    {
        // statuses
        $this->statuses = array(Order::STATUS_ALL => _('All statuses')) + Order::getOrderStatusList();

    }
}
