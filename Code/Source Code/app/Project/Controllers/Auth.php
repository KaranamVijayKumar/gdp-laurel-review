<?php
/**
 * File: Auth.php
 * Created: 11-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Story\Error;

use Story\Validator;

/**
 * Class Auth
 *
 * @package Project\Controllers
 */
class Auth extends AbstractPage
{

    /**
     * View template
     *
     * @var string
     */
    public $template = 'auth/login';

    /**
     * Presents the login page
     */
    public function get()
    {

        if (\Story\Auth::check()) {
            redirect(
                action('\Project\Controllers\Account\Dashboard'),
                array('notice' => _('Already signed in.'))
            );
        }

        try {
            $this->buildPageWithFallback(
                array(
                    'title'   => _('Sign In'),
                    'content' => '<p>' .
                        _('Please sign in to your Laurel Review account or create a new account.') .
                        '</p>'
                ),
                $this->template,
                null
            );
        } catch (\Exception $e) {
            Error::exception($e);
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

            $url = $session->get('back_url') ?: action('\Project\Controllers\Account\Dashboard');

            // we do not remove the url if the user needs to change it's password
            $user = \Story\Auth::user();

            if (!$user->change_password) {
                $session->remove('back_url');
            }

            // user event
            event('user.login');

            redirect($url);
        }

        redirect(action('\Project\Controllers\Auth'), array('error' => _('Invalid credentials supplied.')));

    }
}
