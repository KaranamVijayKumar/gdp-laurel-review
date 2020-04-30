<?php
/**
 * File: Activate.php
 * Created: 19-12-2014
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
use Story\Error;

class Activate extends BaseController
{
    /**
     * Constructor
     *
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        // if signed in we redirect to the user's account page with already signed in message
        if (Auth::check()) {
            redirect(action('\Project\Controllers\Account\Dashboard'), array('notice' => _('Already signed in.')));
        }


        // we check if we have access to account creation, otherwise we redirect to the sign in page
        // with the message that account creation is disabled
        if (!has_access('account_activate')) {

            redirect(
                action('\Project\Controllers\Auth'),
                array('error' => _('Account activation is temporarily disabled.'))
            );
        }

        require_once SP . 'Project/Support/Events/account_events.php';
        return parent::__construct($route, $dispatch);
    }

    public function get($id = '')
    {

        // if no token supplied, we redirect to the sign in page
        $id = trim(html2text($id));

        if (!$id) {
            redirect(
                action('\Project\Controllers\Auth'),
                array('error' => _('Invalid activation token supplied.'))
            );
        }

        try {
            // find the user based on the token where the user is not expired
            $db = load_database();
            /** @var User $user */
            $user = User::one(
                array(
                    'activation_token' => $id,
                    $db->i('active') . ' != "1"',
                    $db->i('created') . ' > ' . (time() - (User::INACTIVE_USER_EXPIRES * 60))
                )
            );

            // no user?
            if (!$user) {
                redirect(
                    action('\Project\Controllers\Auth'),
                    array('error' => _('Invalid activation token supplied.'))
                );
            }

            // activate the user
            $user->activateUser();

            event('account.created_active', $user);

            // redirect to sign in
            redirect(
                action('\Project\Controllers\Auth'),
                array('notice' => _('Your account is now activated.'))
            );


        } catch (\Exception $e) {
            Error::exception($e);
        }




    }
}
