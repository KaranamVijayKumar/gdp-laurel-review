<?php
/*!
 * Password.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2014 Arpad Olasz
 * All rights reserved.
 *
 */


namespace Project\Controllers\Admin\Account;

use Project\Controllers\Admin\AdminBaseController;
use Story\Auth;

/**
 * Class AccountPassword
 *
 * @package Project\Controllers\Admin
 */
class Password extends AdminBaseController
{

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/account/password';

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
     * Shows the password page
     */
    public function get()
    {

        $this->title = _('Account');
        $this->user = Auth::user();

    }

    /**
     * Updates the user password
     */
    public function post()
    {

        try {
            $this->user = Auth::user();
            // Load the user's profile
            $this->user->profiles->load();

            // Update the email and name
            if (($result = $this->user->updateSelfPassword($_POST)) === true) {

                redirect(
                    action('\Project\Controllers\Admin\Account\Dashboard', array($this->user->id)),
                    array('notice' => _('Password changed.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Admin\Account\Password'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            // redirect back to the user list
            redirect(action('\Project\Controllers\Admin\Account\Dashboard'), array('error' => $e->getMessage()));
        }
    }
}
