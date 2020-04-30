<?php
/**
 * File: AdminBaseController.php
 * Created: 30-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin;

use Project\Controllers\BaseController;
use Story\Auth;
use Story\Controller;
use Story\Dispatch;
use Story\URL;

/**
 * Class AdminBaseController
 *
 * @package Project\Controllers\Admin
 */
class AdminBaseController extends BaseController
{

    /**
     * @var array
     */
    public $app;

    /**
     * @var array
     */
    public $errors = array();

    /**
     * @var string
     */
    public $template = 'admin/index';

    /**
     * Constructor
     *
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        $this->app = app();

        parent::__construct($route, $dispatch);
    }

    /**
     * Called after the controller is loaded, before the method
     *
     * @param string $method name
     */
    public function initialize($method)
    {

        /** @var \Story\Session $session */
        $session = $this->app['session'];
        // set the errors in the view
        $this->errors = $session->get('__errors', array());

        // user needs to be logged in to access any of the admin pages
        if (!Auth::check() && !$this->isNoLoginController($this)) {
            // redirect to the admin login page
            $session->put('back_url', URL::current());
            $session->flash('error', _('You need to sign in.'));
            redirect(action('\Project\Controllers\Admin\Auth'));
        }


        // if the user is signed in, we check if he needs to change their password
        $user = Auth::check() ? Auth::user() : false;
        $action = '\Project\Controllers\Admin\Account\ChangePassword';
        if ($user && $user->change_password && '\\' . get_class($this) !== $action &&
            !$this->isNoLoginController($this)) {

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

        // check for permissions
        $name = get_permission_name($method, get_class($this));
        putenv('PERMISSION_NAME='.$name);

        if (!$this->isNoLoginController($this) && !has_access($name)) {
            $this->show403();
            $this->send();
            event('system.shutdown', $this);
            exit;
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
            '\Project\Controllers\Admin\Auth', // login page
            '\Project\Controllers\Admin\Logout', // logout page
            '\Project\Controllers\Admin\Account\Forgot', // password page
            '\Project\Controllers\Admin\Account\ResetPassword', // password reset page
        );

        return in_array('\\' . get_class($controller), $no_login_routes);
    }
}
