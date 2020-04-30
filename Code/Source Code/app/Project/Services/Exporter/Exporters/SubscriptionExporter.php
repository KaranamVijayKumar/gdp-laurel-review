<?php
/**
 * File: SubscriptionExporter.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Exporter\Exporters;

use Project\Models\Export;
use Project\Models\Order;
use Project\Models\Subscription;
use Project\Models\SubscriptionCategory;
use Project\Services\Exporter\AbstractExporter;
use Project\Services\Exporter\ExporterInterface;
use Story\Collection;

/**
 * Class SubscriptionExporter
 * @package Project\Services\Exporter\Exporters
 */
class SubscriptionExporter extends AbstractExporter implements ExporterInterface
{
    /**
     * Category name
     */
    const CATEGORY_NAME = 'Subscription';
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
            $order = Subscription::$db->i(Subscription::getTable() . '.created') . ' ASC';
        }

        // get the models based on the data
        if ($data['query']) {
            $items = Subscription::listSubscriptionsByQuery(
                $data['query'],
                $data['quantity'] == 'current' ? $data['page'] : 1,
                $data['quantity'] == 'current' ? config('per_page') : static::LIMIT,
                $data['status'],
                $data['expiration'],
                $order
            );
        } else {
            $items = Subscription::listSubscriptions(
                $data['quantity'] == 'current' ? $data['page'] : 1,
                $data['quantity'] == 'current' ? config('per_page') : static::LIMIT,
                $data['status'],
                $data['expiration'],
                null,
                $order
            );
        }

        $this->getUsers($items['items']);

        $this->getCategories($items['items']);

        // build the payloads
        $export_data->payload = new Collection;
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
     * Gets the categories and associate it with the items
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getCategories(Collection $items)
    {
        $category_ids = $items->lists('subscription_category_id');

        if (!count($category_ids)) {
            return new Collection;
        }

        $category_ids = array_unique($category_ids);

        $db = SubscriptionCategory::$db;
        $categories = SubscriptionCategory::all(array("{$db->i('id')} IN (" . implode(',', $category_ids) . ")"));
        if ($categories) {
            foreach ($categories as $k => $row) {
                $categories[$k] = new SubscriptionCategory($row);
            }
        }

        $categories = new Collection($categories);

        foreach ($categories as $category) {
            $item = $items->findBy('subscription_category_id', $category->id);
            $item->related['category'] = $category;
        }

        return $categories;
    }


    /**
     * Returns the available columns
     *
     * @return array
     */
    public function getColumns()
    {
        $cols = array(
            'category'     => _('Category'),
            'user'         => _('User'),
            'order_id'     => _('Order ID'),
            'name'         => _('Name'),
            'interval'     => _('Interval'),
            'amount'       => _('Amount'),
            'status'       => _('Status'),
            'description'  => _('Notes'),
            'created'      => _('Created'),
            'modified'     => _('Last Modified'),
            'starts'       => _('Starts'),
            'expires'      => _('Expires'),
            'user_name'    => _('User Name'),
            'user_email'   => _('User Email'),
            'user_address' => _('User Address'),
            'user_phone'   => _('User Phone'),
        );

        natsort($cols);

        return $cols;
    }


    /**
     * Builds the category cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildCategoryCell(Subscription $item)
    {
        if (!isset($item->related['category'])) {
            return false;
        }

        return $item->related['category']->name . "\n" . $item->related['category']->description;
    }

    /**
     * Builds the created cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildCreatedCell(Subscription $item)
    {
        return $item->created->toDayDateTimeString();
    }

    /**
     * Builds the description cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildDescriptionCell(Subscription $item)
    {
        return $item->description;
    }

    /**
     * Builds the expires cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildExpiresCell(Subscription $item)
    {
        return $item->expires->toDayDateTimeString();
    }

    /**
     * Builds the interval cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildIntervalCell(Subscription $item)
    {
        return $item->interval;
    }

    /**
     * Builds the modified cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildModifiedCell(Subscription $item)
    {
        return $item->attributes['modified'] ? $item->modified->toDayDateTimeString() : false;
    }

    /**
     * Builds the name cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildNameCell(Subscription $item)
    {
        return $item->name;
    }

    /**
     * Builds the order id cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildOrderIdCell($item)
    {
        return $item->order_id ? Order::generateOrderId($item->order_id) : false;
    }

    /**
     * Builds the starts cell
     *
     * @param Subscription $item
     *
     * @return string
     */
    public function buildStartsCell(Subscription $item)
    {
        return $item->starts->toDayDateTimeString();
    }
}
