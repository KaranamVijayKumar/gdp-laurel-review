<?php
/**
 * File: ChangePassword.php
 * Created: 18-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Account;

use Project\Controllers\BaseController;
use Project\Models\User;
use Story\Auth;
use Story\Dispatch;

/**
 * Class ChangePassword
 *
 * @package Project\Controllers\Account
 */
class ChangePassword extends BaseController
{

    /**
     * @var string
     */
    public $template = 'account/force_change_password';

    /**
     * @var string
     */
    public $title;

    /**
     * @var User
     */
    public $user;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        if (Auth::check() && $user = Auth::user()) {
            if (!$user->change_password) {
                /** @var \Story\Session $session */
                $session = app('session');
                // if the user doesn't need to change their password we redirect
                $url = $session->get('back_url') ?: action('\Project\Controllers\Account\Dashboard');
                redirect(
                    $url,
                    array(
                        'error' => _(
                            'In order to change your password, go to your account and navigate to the password page.'
                        )
                    )
                );
            }
        }


        return parent::__construct($route, $dispatch);
    }


    /**
     * Presents the password change page
     */
    public function get()
    {

        $this->title = _('Change your password');
    }

    /**
     * Changes the user password
     */
    public function post()
    {

        try {
            $this->user = Auth::user();


            // Change the password
            if (($result = $this->user->changeSelfPassword($_POST)) === true) {
                /** @var \Story\Session $session */
                $session = app('session');

                $url = $session->get('back_url') ?: action('\Project\Controllers\Account\Dashboard');

                $session->remove('back_url');

                // user event
                event('user.login');

                redirect(
                    $url,
                    array('notice' => _('Password changed.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Account\ChangePassword'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            // redirect back to the user list
            redirect(action('\Project\Controllers\Account\ChangePassword'), array('error' => $e->getMessage()));
        }
    }
}
