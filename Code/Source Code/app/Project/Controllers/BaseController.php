<?php
/**
 * File: BaseController.php
 * Created: 24-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Project\Models\UserData;
use Story\Auth;
use Story\Controller;
use Story\Dispatch;
use Story\ORM;
use Story\Session;
use Story\URL;
use Story\View;
use StoryCart\Cart;
use StoryCart\CartItemRepository;
use StoryCart\CartRepository;
use StoryEngine\StoryEngine;

/**
 * Class BaseController
 *
 * @package Project\Controllers
 */
class BaseController extends Controller
{

    /**
     * @var array
     */
    public $app;

    /**
     * @var string
     */
    public $content = '';

    /**
     * @var StoryEngine
     */
    public $engine;

    /**
     * @var array
     */
    public $errors;

    /**
     * @var string
     */
    public $template = 'pages/page';

    /**
     * @param          $route
     * @param Dispatch $dispatch
     *
     */
    public function __construct($route, Dispatch $dispatch)
    {

        $this->app = app();
        $return = parent::__construct($route, $dispatch);


        event(
            'system.shutdown',
            null,
            function () {

                // Save and commit user data

                // we only save when the user is signed in
                if (!Auth::check()) {
                    return true;
                }

                return UserData::storeData();
            }
        );

        // initialize the shopping cart
        $this->initCart();

        // initialize the cms engine
        $this->initEngine();

        return $return;
    }

    /**
     * Initializes the shopping cart
     *
     */
    protected function initCart()
    {
        if (has_access('cart_index')) {


            $user = Auth::user();
            /** @var Session $session */
            $session = $this->app['session'];
            $session_id = $session->getId();

            $user_id = null;
            if ($user) {
                $user_id = $user->id;
            }
            // restore the cart
            global $app;
            $app['cart'] = $this->app['cart'] = $cart = Cart::restore($session_id, $user_id);

            // include the events
            require_once SP . 'Project/Support/Events/cart_events.php';

            // set the user_id and session_id
            $cart->repository->session_id = $session->getId();
            $cart->repository->user_id = $user_id;

            event(
                'system.shutdown',
                null,
                function () use ($cart) {

                    // Save and commit the cart
                    if (count($cart->all())) {
                        $cart->commit();
                    }

                    // do a sweep on expired carts (no user and older then 31 days)
                    if (mt_rand(1, 100) <= 2) {

                        $db = CartRepository::$db;

                        // get expired carts
                        $e = time() - 2678400;
                        $expired_carts = CartRepository::lists(
                            'id',
                            null,
                            array("({$db->i('created')} < {$e} OR {$db->i('modified')} < {$e})")
                        );
                        // do we have expired carts?
                        if (count($expired_carts)) {
                            // get the cart items
                            $cart_items = CartItemRepository::all(
                                array(
                                    "{$db->i('cart_id')} IN (" . implode(',', $expired_carts) . ")"
                                )
                            );

                            // for each cart item, remove it from the cart, this will trigger also
                            // the cleanup of the submission files
                            if ($cart_items) {
                                foreach ($cart_items as $k => $item) {
                                    $item = new CartItemRepository($item);
                                    $item->type_payload->removeFromCart($item);
                                }
                            }
                            // then finally we delete the expired carts
                            CartRepository::$db->delete(
                                "DELETE FROM {$db->i(CartRepository::getTable())} " .
                                "WHERE {$db->i('id')} IN (" .
                                rtrim(str_repeat('?, ', count($expired_carts)), ', ') . ')',
                                $expired_carts
                            );
                        }
                    }
                }
            );
        }
    }

    /**
     * Initialize the cms engine
     */
    protected function initEngine()
    {
        // engine is initialized in the system.startup event (/Project/events.php)
        $this->engine = $this->app['storyengine'];
    }

    /**
     * Called after the controller is loaded, before the method
     *
     * @param string $method name
     */
    public function initialize($method)
    {

        /** @var Session $session */
        $session = $this->app['session'];

        // set the errors in the view
        $this->errors = $session->get('__errors', array());

        $name = get_permission_name($method, get_class($this));

        putenv('PERMISSION_NAME=' . $name);

        // do we have access to that route?
        if (!$this->isNoLoginController($this) && !has_access($name)) {

            // no access and not a login page

            // if logged in send a 404 page
            if (Auth::check()) {
                $this->show403();
            } else {


                // store the back url
                $session->put('back_url', URL::current());

                // if not logged in, suggest login
                $session->flash('error', _('You need to sign in order to continue.'));

                // and redirect
                redirect(action('\Project\Controllers\Auth'));
            }

            $this->send();
            event('system.shutdown', $this);
            exit;
        }

        if (Auth::check()) {

            // if the user is signed in, we check if he needs to change their password
            $user = Auth::user();
            $action = '\Project\Controllers\Account\ChangePassword';
            if ($user->change_password && '\\' . get_class($this) !== $action &&
                !$this->isNoLoginController($this)
            ) {

                // redirect to password change
                redirect(
                    action($action),
                    array('error' => _('You need to change your password before continuing.'))
                );
            }

            header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }
    }

    /**
     * Return true if the controller is a no login one
     *
     * @param Controller $controller
     *
     * @return bool
     */
    protected function isNoLoginController(Controller $controller)
    {

        // reserved routes that doesn't need redirect or need permission check
        $no_login_routes = array(
            '\Project\Controllers\NotFoundController', // not found
            '\Project\Controllers\Auth', // login page
            '\Project\Controllers\Logout', // logout page
            '\Project\Controllers\Account\Forgot', // password page
            '\Project\Controllers\Account\ResetPassword', // reset password page
            '\Project\Controllers\Account\Create', // create account
            '\Project\Controllers\Account\Activate', // activate account
//            '\Project\Controllers\Newsletter\Subscribe', // newsletter subscribe
//            '\Project\Controllers\Newsletter\Unsubscribe', // newsletter unsubscribe
//            '\Project\Controllers\Newsletter\Index', // newsletter
        );

        return in_array('\\' . get_class($controller), $no_login_routes);
    }

    /**
     * Show a 404 error page
     */
    public function show403()
    {

        headers_sent() || header('HTTP/1.0 403 Forbidden');
        $this->template = 'pages/403';
        $this->content = new View('errors/403');
        $this->content->set($this);
    }

    /**
     * Save user session and render the final layout template
     */
    public function send()
    {

        event('controller.beforeSend', $this);

        // Is this a json request?
        if ($this->isJson()) {

            if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'])) {
                headers_sent() || header('Content-Type: text/html; charset=utf-8');
            } else {
                headers_sent() || header('Content-Type: application/json; charset=utf-8');
            }


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

    /**
     * Load database connection
     *
     * @param string $name
     *
     * @return \Story\DB
     */
    public function loadDatabase($name = 'database')
    {

        return load_database($name);
    }

    /**
     * Show a 404 error page
     */
    public function show404()
    {

        headers_sent() || header('HTTP/1.0 404 Page Not Found');

        $this->template = 'pages/404';
        $this->content = new View('errors/404');
        $this->content->set($this);
    }
}
