<?php
/*!
 * Delete.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2014 Arpad Olasz
 * All rights reserved.
 *
 */


namespace Project\Controllers\Admin\Account;

use Project\Controllers\Admin\AdminBaseController;
use Story\Auth;
use Story\Validator;

/**
 * Class AccountDelete
 *
 * @package Project\Controllers\Admin
 */
class Delete extends AdminBaseController
{

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/account/delete';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @var \Project\Models\User
     */
    public $user;

    /**
     * Show the user delete page
     */
    public function get()
    {

        $this->title = _('Account');
        $this->user = Auth::user();

    }

    /**
     * Deletes the user
     */
    public function delete()
    {
        $validator = new Validator($_POST);
        $this->user = Auth::user();

        $validator->rule('required', 'password');
        $validator->rule('password', 'password', 'users', 'password', $this->user->id)
            ->message(_('{field} was entered incorrectly.'));

        if ($validator->validate() && $this->user->delete()) {
            // Sign the user out
            Auth::logout();

            /** @var \Story\Session $session */
            $session = app('session');

            $session->flush();

            event('user.logout');

            redirect(action('\Project\Controllers\Admin\Auth'), array('notice' => 'Account deleted.'));
        }

        // if errors we display them
        redirect(
            action('\Project\Controllers\Admin\Account\Delete'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $validator->errorsToNotification(),
            )
        );
    }
}
