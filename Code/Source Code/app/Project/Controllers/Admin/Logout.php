<?php
/**
 * File: Logout.php
 * Created: 30-07-2014
 *
 * Logout route
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin;

use Story\Auth;

/**
 * Class Logout
 *
 * @package Project\Controllers\Admin
 */
class Logout extends AdminBaseController
{
    /**
     * Logs the current user out
     */
    public function run()
    {

        // Sign the user out
        Auth::logout();

        /** @var \Story\Session $session */
        $session = app('session');

        $session->flush();

        event('user.logout');

        redirect(action('\Project\Controllers\Admin\Auth'), array('notice' => 'Signed out.'));
    }
}
