<?php
/**
 * File: Payment.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\ORM;

/**
 * Class Payment
 *
 * @package Project\Models
 */
class Payment extends ORM
{

    /**
     * @var string
     */
    protected static $table = 'payments';

    /**
     * Returns true if the payment already exists with the same status
     *
     * @param int $id
     * @param string $type
     * @param string $payment_status
     *
     * @return bool
     */
    public static function isDuplicate($id, $type, $payment_status)
    {

        $payment = static::one(
            array('payable_id' => $id, 'payable_type' => $type, 'payment_status' => $payment_status)
        );

        return (bool) $payment;
    }

    /**
     * Payment data accessor
     *
     * @param $value
     * @return mixed
     */
    public function getPaymentDataAttribute($value)
    {
        return json_decode($value);
    }
    /**
     * Returns a collection of payments for  a model
     *
     * @param $model
     * @param null $payable_id
     * @param int $limit
     * @param int $offset
     * @param null $order
     * @return Collection
     */
    public static function many($model, $payable_id = null, $limit = 0, $offset = 0, $order = null)
    {
        if ($model instanceof ORM) {
            $payable_type = get_class($model);
            $payable_id = $model->id;
        } else {
            $payable_type = $model;
        }
        $rows = static::all(
            array(
                'payable_type' => $payable_type,
                'payable_id'   => $payable_id
            ),
            $limit,
            $offset,
            $order
        );

        if ($rows) {
            foreach ($rows as $key => $row) {
                $rows[$key] = new static($row);
            }
        }

        return new Collection($rows);
    }

    /**
     * Creates a payment summary array
     *
     * @return array
     */
    public function paymentSummary()
    {
        $return = array();

        $keys = array(

            'invoice',
            'txn_id',
            'payment_status',
            'first_name',
            'last_name',
            'payer_id',
            'payer_email',
            'payment_date',
        );
        $data = $this->payment_data;
        foreach ($keys as $key) {

            if (isset($data->$key)) {
                $return[$key] = $data->$key;
            }

        }

        return $return;
    }
}
