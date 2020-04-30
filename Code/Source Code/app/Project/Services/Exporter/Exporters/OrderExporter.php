<?php
/**
 * File: OrderExporter.php
 * Created: 28-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Exporter\Exporters;

use Project\Models\Export;
use Project\Models\Order;
use Project\Models\OrderItem;
use Project\Models\OrderUser;
use Project\Services\Exporter\AbstractExporter;
use Project\Services\Exporter\ExporterInterface;
use Story\Collection;
use Story\ORM;

/**
 * Class OrderExporter
 * @package Project\Services\Exporter\Exporters
 */
class OrderExporter extends AbstractExporter implements ExporterInterface
{
    /**
     * Category name
     */
    const CATEGORY_NAME = 'Order';
    const LIMIT = 8000;

    /**
     * Returns the category name
     *
     * @return string
     */
    public function getCategoryName()
    {
        return static::CATEGORY_NAME;
    }

    /**
     * Returns the available columns
     *
     * @return array
     */
    public function getColumns()
    {
        $cols = array(
            'order_id'       => _('Order ID'),
            'order_status'   => _('Order Status'),
            'sub_total'      => _('Sub Total'),
            'tax_precentage' => _('Tax (%)'),
            'tax'            => _('Tax'),
            'order_total'    => _('Order Total'),
            'currency'       => _('Currency'),
            'created'        => _('Created'),
            'modified'       => _('Last Modified'),
            'user'           => _('User'),
            'user_name'      => _('User Name'),
            'user_email'     => _('User Email'),
            'user_address'   => _('User Address'),
            'user_phone'     => _('User Phone'),
            'items'          => _('Items')
        );

        natsort($cols);

        return $cols;
    }

    /**
     * Builds the export data
     *
     * @param Export $export
     * @param array  $data
     *
     * @return \stdClass
     */
    public function build(Export $export, array $data)
    {
        $export_data = new \stdClass();
        // Build the name
        $export_data->name = $export->buildNameWithTimestamp();

        $order = null;

        if ($data['quantity'] == 'all') {
            $order = Order::$db->i(Order::getTable() . '.created') . ' ASC';
        }

        $export_data->payload = new Collection;
        // get the models based on the data

        if ($data['query']) {
            $items = Order::listOrdersByQuery(
                $data['query'],
                $data['quantity'] == 'current' ? $data['page'] : 1,
                $data['quantity'] == 'current' ? config('per_page') : static::LIMIT,
                $data['status'],
                $order
            );
        } else {
            $items = Order::listOrders(
                $data['quantity'] == 'current' ? $data['page'] : 1,
                $data['quantity'] == 'current' ? config('per_page') : static::LIMIT,
                $data['status'],
                null,
                $order
            );
        }

        $this->getOrderUsers($items['items']);

        if (in_array('items', $export->columns)) {
            $this->getOrderItems($items['items']);
        }

        // build the payloads
        foreach ($items['items'] as $row) {

            $cells = array();
            // call for all columns the column function
            foreach ($export->columns as $column) {
                $fct = 'build' . studly($column) . 'Cell';
                $cells[] = call_user_func(array($this, $fct), $row);
            }

            $export_data->payload->push($cells);
        }

        $export_data->headers = $this->buildHeaders($export->columns);

        return $export_data;
    }

    /**
     * Returns and associates the order user with the order
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getOrderUsers(Collection $items)
    {
        $order_ids = $items->lists('id');

        if (!count($order_ids)) {
            return new Collection;
        }

        $order_ids = array_unique($order_ids);

        $db = OrderUser::$db;
        $order_users = OrderUser::all(array("{$db->i('order_id')} IN (" . implode(',', $order_ids) . ")"));
        if ($order_users) {
            foreach ($order_users as $k => $row) {
                $order_users[$k] = new OrderUser($row);
            }
        }

        $order_users = new Collection($order_users);

        foreach ($order_users as $order_user) {

            // get the order by id
            /** @var Order $order */
            $order = $items->findBy('id', $order_user->order_id);

            if ($order) {
                if (!isset($order->related['order_user'])) {
                    $order->related['order_user'] = new Collection;
                }
                $order->related['order_user']->push($order_user);
            }
        }

        return $order_users;
    }

    /**
     * @param Collection $items
     *
     * @return array|Collection
     */
    public function getOrderItems(Collection $items)
    {
        $order_ids = $items->lists('id');

        if (!count($order_ids)) {
            return new Collection;
        }

        $order_ids = array_unique($order_ids);

        $db = OrderItem::$db;
        $order_items = OrderItem::all(array("{$db->i('order_id')} IN (" . implode(',', $order_ids) . ")"));

        if ($order_items) {
            foreach ($order_items as $k => $row) {
                $order_items[$k] = new OrderItem($row);
            }
        }

        $order_items = new Collection($order_items);

        foreach ($order_items as $order_item) {
            // get the order by id
            /** @var Order $order */
            $order = $items->findBy('id', $order_item->order_id);

            if ($order) {
                $order->related['items'][] = $order_item;
            }
        }

        return $order_items;
    }

    /**
     * Builds the created cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildCreatedCell(ORM $item)
    {
        return $item->created->toDayDateTimeString();
    }

    /**
     * Builds the created cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildModifiedCell(ORM $item)
    {
        return $item->modified->toDayDateTimeString();
    }

    /**
     * Builds the currency cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildCurrencyCell(ORM $item)
    {
        return $item->currency;
    }

    /**
     * Builds the items cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildItemsCell(ORM $item)
    {
        $return = '';

        foreach ($item->items as $item) {
            $item_data = $item->item_data;

            $return .= '(' . $item->quantity . ') ' . $item_data->getOrderType() . ': ' . $item_data->getName();
            $return .= ' - ' . get_formatted_currency($item->price);
            $return .= "\n";
        }

        return trim($return);
    }


    /**
     * Builds the order total cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildOrderTotalCell(ORM $item)
    {
        return get_formatted_currency($item->order_total);
    }

    /**
     * Builds the order sub-total cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildSubTotalCell(ORM $item)
    {
        return get_formatted_currency($item->sub_total);
    }

    /**
     * Builds the order tax cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildTaxCell(ORM $item)
    {
        return get_formatted_currency($item->order_total * ($item->tax / 100));
    }

    /**
     * Builds the order tax precentage cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildTaxPrecentageCell(ORM $item)
    {
        return $item->tax;
    }

    /**
     * Builds the order id cell
     *
     * @param Order $item
     *
     * @return string
     */
    public function buildOrderIdCell($item)
    {
        return $item->orderId();
    }

    /**
     * Builds the order status cell
     *
     * @param Order $item
     *
     * @return string
     */
    public function buildOrderStatusCell(Order $item)
    {
        static $status_list = false;

        if ($status_list === false) {
            $status_list = Order::getOrderStatusList();
        }

        return $status_list[$item->order_status];
    }


    /**
     * Builds the user cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserCell(ORM $item)
    {

        // add the name
        if (!$item->order_user) {
            return false;
        }
        $return = "";


        // name
        $name = $item->order_user->findBy('name', 'name');
        $return .= $name ? $name->value : '';

        // email
        $name = $item->order_user->findBy('name', 'email');
        $return .= $name ? "\nEmail: {$name->value}" : '';

        // phone
        $phone = $item->order_user->findBy('name', 'phone');
        $return .= $phone && $phone->value ? "\nPhone: " . $phone->value . "\n" : '';


        // address
        $address = $item->order_user->findBy('name', 'address');
        $return .= $address ? "\n" . $address->value : '';
        $address2 = $item->order_user->findBy('name', 'address2');
        $return .= $address2 ? "\n" . $address2->value : '';
        $city = $item->order_user->findBy('name', 'city');
        $return .= $city ? "\n" . $city->value : '';
        $state = $item->order_user->findBy('name', 'state');
        $return .= $state ? " " . $state->value : '';
        $zip = $item->order_user->findBy('name', 'zip');
        $return .= $zip ? " " . $zip->value : '';
        // country
        $country = $item->order_user->findBy('name', 'country');
        $return .= $country ? "\n" .
            (isset($this->countries[$country->value]) ? $this->countries[$country->value] : $country->value) : '';

        return trim($return);
    }

    /**
     * Builds the user name cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserNameCell(ORM $item)
    {

        // add the name
        if (!$item->order_user) {
            return false;
        }

        // name
        $name = $item->order_user->findBy('name', 'name');

        return $name ? $name->value : '';
    }

    /**
     * Builds the user email cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserEmailCell(ORM $item)
    {
        // add the name
        if (!$item->order_user) {
            return false;
        }

        // email
        $name = $item->order_user->findBy('name', 'email');

        return $name ? $name->value : '';
    }

    /**
     * Builds the user address cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserAddressCell(ORM $item)
    {

        // add the name
        if (!$item->order_user) {
            return false;
        }
        $return = "";

        // address
        $address = $item->order_user->findBy('name', 'address');
        $return .= $address ? "\n" . $address->value : '';
        $address2 = $item->order_user->findBy('name', 'address2');
        $return .= $address2 ? "\n" . $address2->value : '';
        $city = $item->order_user->findBy('name', 'city');
        $return .= $city ? "\n" . $city->value : '';
        $state = $item->order_user->findBy('name', 'state');
        $return .= $state ? " " . $state->value : '';
        $zip = $item->order_user->findBy('name', 'zip');
        $return .= $zip ? " " . $zip->value : '';
        // country
        $country = $item->order_user->findBy('name', 'country');
        $return .= $country ? "\n" .
            (isset($this->countries[$country->value]) ? $this->countries[$country->value] : $country->value) : '';

        return trim($return);
    }

    /**
     * Builds the user phone cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildUserPhoneCell(ORM $item)
    {

        // add the name
        if (!$item->order_user) {
            return false;
        }

        // phone
        $phone = $item->order_user->findBy('name', 'phone');

        return $phone && $phone->value ? $phone->value : '';
    }
}
