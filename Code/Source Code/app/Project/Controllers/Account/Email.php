<?php
/**
 * File: Email.php
 * Created: 11-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Account;

use Project\Controllers\AbstractPage;
use Project\Models\Profile;
use Story\Auth;

use Story\Error;
use Story\NotFoundException;

/**
 * Class Email
 *
 * @package Project\Controllers\Account
 */
class Email extends AbstractPage
{

    /**
     * @var Profile
     */
    public $default;

    /**
     * View template
     *
     * @var string
     */
    public $template = 'account/email';

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
     * Returns the template
     */
    public function get()
    {
        try {

            $this->user = Auth::user();
            $this->default = new Profile;
            $this->default->set(array('value' => ''));
            $this->buildPage(null, $this->template);

        } catch (NotFoundException $e) {
            $this->title = _('Name &amp; Email');

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }

    /**
     *
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
                    action('\Project\Controllers\Account\Email', array($this->user->id)),
                    array('notice' => _('Saved.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Account\Email'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result,
                )
            );

        } catch (\Exception $e) {
            redirect(action('\Project\Controllers\Account\Dashboard'), array('error' => $e->getMessage()));
        }
    }
}
