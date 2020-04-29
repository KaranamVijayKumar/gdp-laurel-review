<?php
/**
 * File: Payment.php
 * Created: 09-10-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Billing\Paypal;

use Project\Models\Order;
use Project\Services\Billing\PaymentInterface;
use Project\Services\Cart\OrderableInterface;
use Story\Error;
use Story\Form;
use Story\HTML;
use Story\ORM;
use StoryCart\OrderRepository;

/**
 * Class Payment
 *
 * @package Project\Services\Billing\Paypal
 */
class Payment implements PaymentInterface
{

    /**
     * Paypal configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->config = array(
            'sandbox'        => (bool)getenv('PAYPAL_SANDBOX'),
            'merchant_id'    => getenv('PAYPAL_MERCHANT_ID'),
            'merchant_email' => getenv('PAYPAL_MERCHANT_EMAIL'),
            'size'           => 'large'
        );
    }

    /**
     * Returns the payment data based on the payment model
     *
     * @param \Project\Models\Payment $payment
     *
     * @param                         $name
     * @param                         $return_url
     * @param                         $cancel_url
     *
     * @param string                  $currency
     *
     * @return array
     */
    public function paymentData(\Project\Models\Payment $payment, $name, $return_url, $cancel_url, $currency = 'USD')
    {

        return array(
            'quantity'      => 1,
            'amount'        => $payment->amount,
            'no_shipping'   => 1,
            'return'        => $return_url,
            'name'          => $name,
            'cancel_return' => $cancel_url,
            'currency'      => $currency,
            'custom'        => base64_encode(
                json_encode(
                    array('payable_id' => $payment->payable_id, 'payable_type' => $payment->payable_type)
                )
            )

        );
    }

    /**
     * Returns the payment url
     *
     * @return string
     */
    public function getUrl()
    {

        $url = 'https://www.paypal.com';
        if ($this->config['sandbox']) {
            $url = 'https://www.sandbox.paypal.com';
        }

        $url .= '/cgi-bin/webscr';

        return $url;
    }

    /**
     * Returns the payment label
     *
     * @return string
     */
    public function getLabel()
    {

        $label = <<<EOF
<a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" class="1/1" border="0" style="margin-top:-10px;" alt="PayPal Acceptance Mark"></a>
EOF;

        return $label;
    }

    /**
     * Updates the payment based on the paypal's post data
     *
     * @param array $input
     *
     * @return bool
     */
    public function update($input)
    {

        $payment = new \Project\Models\Payment;
        // we verify first the payment data
        if ($this->verify($input)) {
            // then we create a new payment entry with the data if the current payment status doesn't exists

            try {

                // decode the custom field
                $payable = $this->decodeCustomField($input['custom']);

                if (!$payable) {
                    return false;
                }

                // we check if we have a payment with the same status
                if ($payment::isDuplicate($payable['payable_id'], $payable['payable_type'], $input['payment_status'])) {
                    return false;
                }

                $payment->set(
                    array(
                        'payable_id'     => $payable['payable_id'],
                        'payable_type'   => $payable['payable_type'],
                        'payment_status' => $input['payment_status'],
                        'payment_data'   => json_encode($input),
                        'amount'         => $input['mc_gross'],
                        'notes'          => 'Payment updated.',
                        'created'        => time(),
                    )
                );

                $payment->save();

                // call the payEvent on the model
                call_user_func(
                    array($payable['payable_type'], static::MODEL_EVENT),
                    $payable['payable_id'],
                    $input['payment_status']
                );

                return true;
            } catch (\Exception $e) {

                Error::exception($e);
            }
        }

        return false;
    }

    /**
     * Returns true if the received payment data is valid
     *
     * @param array $input
     *
     * @return bool
     */
    public function verify($input)
    {

        if (!is_array($input) || !count($input)) {
            return false;
        }

        // check the merchant
        if (!isset($input['receiver_id']) || $input['receiver_id'] !== $this->config['merchant_id']) {
            return false;
        }

        if (!isset($input['receiver_email']) || $input['receiver_email'] !== $this->config['merchant_email']) {
            return false;
        }

        // check the model
        if (!isset($input['custom']) || !$input['custom']) {
            return false;
        }

        $payable = $this->decodeCustomField($input['custom']);

        if (!isset($payable['payable_id']) || !$payable['payable_id'] ||
            !isset($payable['payable_type']) || !$payable['payable_type']
        ) {
            return false;
        }

        $class = new \ReflectionClass($payable['payable_type']);

        if (!$class->implementsInterface('Project\Services\Billing\PaymentEventInterface')) {
            return false;
        }

        return true;
    }

    /**
     * Decodes the custom variable
     *
     * @param $custom
     *
     * @return null|array
     */
    private function decodeCustomField($custom)
    {

        return @json_decode(@base64_decode($custom), true);
    }

    /**
     * View the payment form
     *
     * @param        $name
     * @param        $amount
     * @param array  $custom
     * @param string $currency
     * @param null   $return_url
     * @param null   $cancel_url
     * @param null   $invoice
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
    ) {

        $data = array(
            'return'        => $return_url,
            'cancel_return' => $cancel_url,
            'currency'      => $currency,
            'custom'        => base64_encode(json_encode($custom)),
            'invoice'       => $invoice,
        );

        if (is_array($name)) {

            foreach ($name as $index => $item_data) {

                $i = $index + 1;
                $data['quantity_' . $i] = $item_data['quantity'];
                $data['amount_' . $i] = $item_data['amount'];
                $data['item_name_' . $i] = $item_data['name'];
                if (isset($item_data['tax'])) {
                    $data['tax_' . $i] = $item_data['tax'];
                }
            }
        } else {
            $data['quantity'] = 1;
            $data['amount'] = $amount;
            $data['name'] = $name;
            $data['no_shipping'] = 1;
        }

        return $this->customCheckoutForm($data);
    }

    /**
     * Creates a custom buy now form
     *
     * @param $data
     *
     * @return string
     */
    public function customCheckoutForm($data)
    {

        if (!isset($data['item_name_1'])) {

            return $this->checkoutForm($data);
        }

        $data['upload'] = 1;
        $data['cmd'] = '_cart';
        $data['business'] = $this->config['merchant_id'];
        $data['notify_url'] = action('Project\Controllers\PaypalIpn');
        $data['rm'] = "2";

        // form
        $form_attributes = array(
            'method' => 'post',
            'action' => $this->getUrl(),
            'class' => 'paypal-button',
            'target' => '_top'
        );

        $html = '<form ' . HTML::attributes($form_attributes) . '>';

        // hidden elements
        foreach ($data as $name => $value) {
            $html .= Form::hidden($name, $value);
        }

        // button
        $html .= Form::button(
            _('Buy Now'),
            array(
                'type' => 'submit',
                'class' => 'paypal-button large'
            )
        );


        $html .= '</form>';

        return $html;
    }

    /**
     * Returns the payment form from order
     *
     * @param OrderRepository $order
     * @param null|string  $return_url
     * @param null|string  $cancel_url
     *
     * @return string
     */
    public function getFormFromOrder(OrderRepository $order, $return_url = null, $cancel_url = null)
    {
        // get the quantity, amount, name for items
        $names = array();

        $order->items->load();

        foreach ($order->items as $item) {
            $item_data = $item->item_data;

            /** @var OrderableInterface $item_data */
            $name = ellipsize(
                $item_data->getOrderType() .': ' .  $item_data->getName(),
                200 - strlen($item_data->getOrderType()),
                1,
                '...'
            );
            $data = array(
                'name' => $name,
                'amount' => $item->price,
                'quantity' => $item->quantity,
                'tax' => $item->price * ($item->tax / 100)
            );
            $names[] = $data;
        }

        return $this->viewPaymentForm(
            $names,
            $order->orderId(),
            $order->order_total,
            array('payable_id' => $order->id, 'payable_type' => get_class($order)),
            $order->currency,
            $return_url,
            $cancel_url
        );
    }

    /**
     * Shows the checkout form
     *
     * @param array $data
     *
     * @return string
     */
    public function checkoutForm($data)
    {

        // We simply create a script element which will generate the paypal button
        $attributes = array(
            'async'         => 'async',
            'src'           => to(
                'themes/' . app('theme')->getName() .
                '/vendor/paypal/button.js?merchant=' . $this->config['merchant_id']
            ),
            'data-callback' => action('Project\Controllers\PaypalIpn'),
            'data-env'      => $this->config['sandbox'] ? 'sandbox' : 'production',
            'data-button'   => 'buynow',
        );

        foreach ($data as $key => $value) {
            $attributes['data-' . $key] = $value;
        }

        return '<script ' . HTML::attributes($attributes) . '></script>';
    }
}
