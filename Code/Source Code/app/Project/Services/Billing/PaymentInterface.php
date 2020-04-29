<?php
/**
 * File: PaymentInterface.php
 * Created: 15-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
namespace Project\Services\Billing;

use Project\Models\Order;
use Project\Models\Payment;
use Story\ORM;
use StoryCart\OrderRepository;

/**
 * Class Payment
 *
 * @package Project\Services\Billing\Paypal
 */
interface PaymentInterface
{
    /**
     * A reversal has been canceled. For example, you won a dispute with the customer,
     * and the funds for the transaction that was reversed have been returned to you.
     */
    const PAYMENT_STATUS_CANCELED_REVERSAL = 'Canceled_Reversal';

    /**
     * The payment has been completed, and the funds have been added successfully to your account balance.
     */
    const PAYMENT_STATUS_COMPLETED = 'Completed';

    /**
     * A German ELV payment is made using Express Checkout
     */
    const PAYMENT_STATUS_CREATED = 'Created';

    /**
     * The payment was denied. This happens only if the payment was previously pending because of one of the reasons
     * listed for the pending_reason variable or the Fraud_Management_Filters_x variable
     */
    const PAYMENT_STATUS_DENIED = 'Denied';

    /**
     * This authorization has expired and cannot be captured
     */
    const PAYMENT_STATUS_EXPIRED = 'Expired';

    /**
     * The payment has failed. This happens only if the payment was made from your customer's bank account.
     */
    const PAYMENT_STATUS_FAILED = 'Failed';

    /**
     * The payment is pending. See pending_reason for more information.
     */
    const PAYMENT_STATUS_PENDING = 'Pending';

    /**
     * You refunded the payment.
     */
    const PAYMENT_STATUS_REFUNDED = 'Refunded';

    /**
     * A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from your
     * account balance and returned to the buyer. The reason for the reversal is specified in the ReasonCode element.
     */
    const PAYMENT_STATUS_REVERSED = 'Reversed';

    /**
     * A payment has been accepted.
     */
    const PAYMENT_STATUS_PROCESSED = 'Processed';

    /**
     * This authorization has been voided.
     */
    const PAYMENT_STATUS_VOIDED = 'Voided';

    /**
     * Function which is called when the payment is processed or updated
     */
    const MODEL_EVENT = 'payEvent';
    
    /**
     * Shows the checkout form
     *
     * @param array $data
     *
     * @return string
     */
    public function checkoutForm($data);

    /**
     * Returns the payment url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Returns the payment data based on the payment model
     *
     * @param Payment                 $payment
     *
     * @param                         $name
     * @param                         $return_url
     * @param                         $cancel_url
     *
     * @param string                  $currency
     *
     * @return array
     */
    public function paymentData(Payment $payment, $name, $return_url, $cancel_url, $currency = 'USD');

    /**
     * Updates the payment based on the paypal's post data
     *
     * @param array $input
     *
     * @return bool
     */
    public function update($input);

    /**
     * Returns true if the received payment data is valid
     *
     * @param array $input
     *
     * @return bool
     */
    public function verify($input);

    /**
     * View the payment form
     *
     * @param        $name
     * @param        $invoice
     * @param        $amount
     * @param array  $custom
     * @param string $currency
     * @param null   $return_url
     * @param null   $cancel_url
     *
     * @return string
     */
    public function viewPaymentForm(
        $name,
        $invoice,
        $amount,
        $custom = array(),
        $currency = 'USD',
        $return_url = null,
        $cancel_url = null
    );

    /**
     * Returns the payment form from order
     *
     * @param OrderRepository $order
     * @param null|string  $return_url
     * @param null|string  $cancel_url
     *
     * @return string
     */
    public function getFormFromOrder(OrderRepository $order, $return_url = null, $cancel_url = null);
}
