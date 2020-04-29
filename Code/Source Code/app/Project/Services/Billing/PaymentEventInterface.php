<?php
/**
 * File: PaymentEvents.php
 * Created: 11-10-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Billing;

interface PaymentEventInterface
{
    /**
     * This function is called when a payment is related to a model
     *
     * @param int $id
     * @param string $status
     *
     * @return bool
     */
    public static function payEvent($id, $status);
}
