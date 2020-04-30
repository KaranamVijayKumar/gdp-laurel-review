<?php
/**
 * File: Auth.php
 * Created: 30-07-2014
 *
 * Login controller
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin;

use Story\Validator;

/**
 * Class Auth
 *
 * @package Project\Controllers\Admin
 */
class Auth extends AdminBaseController
{

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/auth/login';

    /**
     * Page title
     *
     * @var
     */
    public $title;

    /**
     * Shows the login page
     */
    public function get()
    {

        $this->title = _('Sign In');

        if (\Story\Auth::check()) {
            redirect(
                action('\Project\Controllers\Admin\Dashboard'),
                array('notice' => _('Already signed in.'))
            );
        }

    }

    /**
     * Processes the login data and logs the user in
     */
    public function post()
    {

        $validator = new Validator($_POST);
        /** @var \Story\Session $session */
        $session = app('session');

        $validator->rule('required', 'email');
        $validator->rule('email', 'email');

        $validator->rule('required', 'password');

        // validate the form and attempt to sign the user in
        if ($validator->validate() &&
            \Story\Auth::attempt(array('email' => post('email'), 'password' => post('password'), 'active' => 1))
        ) {

            $session->flash('notice', _('Signed in.'));
            $session->flash('notifSymbol', 'info-circle');

            $url = $session->get('back_url') ?: action('\Project\Controllers\Admin\Dashboard');

            // we do not remove the url if the user needs to change it's password
            $user = \Story\Auth::user();

            if (!$user->change_password) {
                $session->remove('back_url');
            }

            // user event
            event('user.login');

            redirect($url);
        }

        redirect(action('\Project\Controllers\Admin\Auth'), array('error' => _('Invalid credentials supplied.')));
    }
}
