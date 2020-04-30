<?php
/*!
 * Email.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2014 Arpad Olasz
 * All rights reserved.
 *
 */


namespace Project\Controllers\Admin\Account;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Profile;
use Story\Auth;

/**
 * Class AccountEmail
 *
 * @package Project\Controllers\Admin
 */
class Email extends AdminBaseController
{

    /**
     * Default profile value. Used when contact information was never filled
     *
     * @var Profile
     */
    public $default;

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/account/email';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Current user
     *
     * @var \Project\Models\User
     */
    public $user;

    /**
     * Shows the user name and email page
     */
    public function get()
    {

        $this->user = Auth::user();
        $this->title = _('Account');
        $this->default = new Profile;
        $this->default->set(array('value' => ''));

    }

    /**
     * Saves the current user's email and name
     */
    public function post()
    {

        try {
            $this->user = Auth::user();
            // Load the user's profile
            $this->user->profiles->load();

            // Update the email and name
            if (($result = $this->user->updateEmailAndName($_POST)) === true) {

                redirect(
                    action('\Project\Controllers\Admin\Account\Dashboard', array($this->user->id)),
                    array('notice' => _('Saved.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Admin\Account\Email'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            redirect(action('\Project\Controllers\Admin\Account\Dashboard'), array('error' => $e->getMessage()));
        }
    }
}
