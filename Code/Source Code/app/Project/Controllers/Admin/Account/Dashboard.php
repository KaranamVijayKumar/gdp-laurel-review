<?php
/*!
 * Dashboard.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2014 Arpad Olasz
 * All rights reserved.
 *
 */


namespace Project\Controllers\Admin\Account;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\UserData;
use Story\Auth;

/**
 * Class Dashboard
 *
 * @package Project\Controllers\Admin\Account
 */
class Dashboard extends AdminBaseController
{

    /**
     * @var \stdClass
     */
    public $default;

    /**
     * @var string
     */
    public $template = 'admin/account/index';

    /**
     * @var string
     */
    public $title;

    /**
     * @var \Project\Models\User
     */
    public $user;

    /**
     * Clears the user data
     */
    public function getClear()
    {

        UserData::clearData();

        redirect(
            action('\Project\Controllers\Admin\Account\Dashboard'),
            array('notice' => _('User data cleared.'))
        );

    }

    /**
     * Catches all requests
     */
    public function run()
    {

        $this->title = _('Account');
        $this->user = Auth::user();
    }
}
