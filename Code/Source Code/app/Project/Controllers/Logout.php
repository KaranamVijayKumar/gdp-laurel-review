<?php
/**
 * File: Logout.php
 * Created: 11-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Story\Auth;

class Logout extends BaseController
{
    /**
     * Logs the current user out
     */
    public function run()
    {

        // Sign the user out
        Auth::logout();

        event('user.logout');
        /** @var \Story\Session $session */
        $session = app('session');

        $session->flush();

        redirect(action('\Project\Controllers\Auth'), array('notice' => 'Signed out.'));
    }
}
