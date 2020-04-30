<?php
/*!
 * Contact.php v0.1
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
 * Class AccountContact
 *
 * @package Project\Controllers\Admin
 */
class Contact extends AdminBaseController
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
    public $template = 'admin/account/contact';

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
     * Shows the contact page for the account
     */
    public function get()
    {

        $this->user = Auth::user();
        $this->title = _('Account');
        $this->default = new Profile;
        $this->default->set(array('value' => ''));

    }

    /**
     * Saves the contact information
     *
     */
    public function post()
    {

        try {
            $this->user = Auth::user();

            // Load the user's profile
            $this->user->profiles->load();

            // Update the contact information for the user
            if (($result = $this->user->updateContact($_POST)) === true) {

                redirect(
                    action('\Project\Controllers\Admin\Account\Dashboard'),
                    array('notice' => _('Saved.'))
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Admin\Account\Contact'),
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
