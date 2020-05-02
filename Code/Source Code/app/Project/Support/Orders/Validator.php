<?php
/**
 * File: Validator.php
 * Created: 06-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Orders;

use Project\Models\Export;
use Project\Models\Order;
use Project\Services\Exporter\ExporterFactoryInterface;

/**
 * Class Validator
 * @package Project\Support\Orders
 */
class Validator extends \Story\Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {
        $data['status'] = trim(html2text($data['status']));
        parent::__construct($data, $fields);
    }

    /**
     * @param array $input
     * @param Order $order
     * @return \Story\Validator
     */
    public static function edit(array $input, Order $order)
    {
        /** @var \Story\Validator $validator */
        $validator = new static($input);

        $validator->rule('required', 'status')
            ->message(_('Status is required.'));

        $validator->rule('in', 'status', array_keys($order::getOrderStatusList()))
            ->message(_('Invalid status selected.'));

        return $validator;
    }

    /**
     * Validates the export parameters
     *
     * @param array                    $input
     * @param ExporterFactoryInterface $exporter
     *
     * @return static
     */
    public static function export(array $input, ExporterFactoryInterface $exporter)
    {
        $v = new static($input);

        // exporter
        $v->rule('required', 'exporter');
        $class_name = get_class($exporter->get('Order'));
        $v->rule(
            'exists',
            'exporter',
            Export::getTable(),
            'id',
            'status',
            '=',
            '1',
            'exporter',
            '=',
            $class_name
        );

        // quantity
        $v->rule('required', 'quantity');
        $v->rule('in', 'quantity', array('all', 'current'));

        // status
        $all_statuses = array_merge(array_keys(Order::getOrderStatusList()), array(Order::STATUS_ALL));
        $v->rule('required', 'status');
        $v->rule(
            'in',
            'status',
            $all_statuses
        );

        // page
        $v->rule('required', 'page');
        $v->rule('numeric', 'page');

        return $v;
    }
}
