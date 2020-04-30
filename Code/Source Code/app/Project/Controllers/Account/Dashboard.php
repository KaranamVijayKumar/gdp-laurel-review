<?php
/**
 * File: Dashboard.php
 * Created: 11-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Account;

use Project\Controllers\AbstractPage;
use Story\Auth;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Dashboard
 *
 * @package Project\Controllers\Account
 */
class Dashboard extends AbstractPage
{

    /**
     * View template
     *
     * @var string
     */
    public $template = 'account/dashboard';

    /**
     * @var string
     */
    public $title;

    /**
     * @var \Project\Models\User
     */
    public $user;

    /**
     * Shows the account dashboard page
     */
    public function run()
    {

        try {

            $this->user = Auth::user();
            $this->buildPage(null, $this->template);

        } catch (NotFoundException $e) {
            $this->title = _('Account');

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }
}
