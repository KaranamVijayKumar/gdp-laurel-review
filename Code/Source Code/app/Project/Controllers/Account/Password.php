<?php
/**
 * File: Password.php
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

class Password extends AbstractPage
{
    /**
     * View template
     *
     * @var string
     */
    public $template = 'account/password';

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
     * Show the password page
     */
    public function get()
    {

        try {
            $this->user = Auth::user();

            $this->buildPage(null, $this->template);

        } catch (NotFoundException $e) {
            $this->title = _('Password');

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }

    /**
     * Processes the user password
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
                    action('\Project\Controllers\Account\Password'),
                    array('notice' => _('Password changed.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Account\Password'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            // redirect back to the user list
            redirect(action('\Project\Controllers\Account\Dashboard'), array('error' => $e->getMessage()));
        }
    }
}
