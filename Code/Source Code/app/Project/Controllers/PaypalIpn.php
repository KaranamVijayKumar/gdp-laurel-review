<?php
/**
 * File: PaypalIpn.php
 * Created: 07-10-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use PayPal\Ipn\Listener;
use PayPal\Ipn\Message;
use PayPal\Ipn\Verifier\CurlVerifier;
use Project\Services\Billing\Paypal\Payment;
use Story\Controller;
use Story\Dispatch;
use Story\View;

/**
 * Class PaypalIpn
 *
 * @package Project\Controllers
 */
class PaypalIpn extends Controller
{

    /**
     * @var View
     */
    public $content;

    /**
     * View template
     *
     * @var string
     */
    public $template;

    /**
     * Paypal config
     *
     * @var array
     */
    protected $config;

    /**
     * Payment handler
     *
     * @var \Project\Services\Billing\Paypal\Payment
     */
    protected $payment;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     *
     */
    public function __construct($route, Dispatch $dispatch)
    {

        $this->app = app();
        $this->config = array(
            'sandbox'        => (bool)getenv('PAYPAL_SANDBOX'),
            'merchant_id'    => getenv('PAYPAL_MERCHANT_ID'),
            'merchant_email' => getenv('PAYPAL_MERCHANT_EMAIL'),
            'size'           => 'large'
        );

        load_database();
        /** @var \Project\Services\Billing\Paypal\Payment $payment */
        $this->payment = new Payment();

        parent::__construct($route, $dispatch);
    }

    /**
     * Validates and processes the paypal ipn message
     */
    public function run()
    {
        $listener = new Listener();
        $verifier = new CurlVerifier();
        $ipnMessage = Message::createFromGlobals();

        $verifier->setIpnMessage($ipnMessage);

        $verifier->setEnvironment($this->config['sandbox'] ? 'sandbox' : 'production');

        $listener->setVerifier($verifier);

        $payment = $this->payment;
        $listener->listen(
            // valid ipn
            function () use ($listener, $payment) {
                // decode the custom field and based on that we insert the payment
                $data = array();
                foreach ($listener->getVerifier()->getIpnMessage() as $k => $v) {
                    $data[$k] = $v;
                }

                $payment->update($data);
                exit;
            }
        );
        exit;
    }

    /**
     * Show a 404 error page
     */
    public function show404()
    {

        headers_sent() || header('HTTP/1.0 404 Page Not Found');
        $this->template = 'pages/404';
        $this->content = new View('errors/404');
    }

    /**
     * Called after the controller is loaded, before the method
     *
     * @param string $method name
     */
    public function initialize($method)
    {

        header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    /**
     * Save user session and render the final layout template
     */
    public function send()
    {

        event('controller.beforeSend');

        // Is this a json request?
        if ($this->isJson()) {
            headers_sent() || header('Content-Type: application/json; charset=utf-8');

            print is_string($this->json) ? $this->json : json_encode($this->json);

        } else {
            headers_sent() || header('Content-Type: text/html; charset=utf-8');


            $layout = new View($this->template);

            $layout->set($this);

            $html = (string)$layout;
            // We are not sending debug on json or when not in debug mode
            if (config('debug') && !$this->isJson()) {
                $debug = new View('errors/debug');
                // inject just before the body
                if (strpos($html, '</body>') !== false) {
                    $html = str_replace('</body>', $debug . '</body>', $html);
                } else {
                    $html .= $debug;
                }
            }


            echo $html;
        }


        event('controller.afterSend');
    }
}
