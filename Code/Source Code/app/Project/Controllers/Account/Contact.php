<?php
/**
 * File: Contact.php
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

class Contact extends AbstractPage
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
    public $template = 'account/contact';

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
     *
     */
    public function get()
    {
        try {

            $this->user = Auth::user();
            $this->default = new Profile;
            $this->default->set(array('value' => ''));
            $this->buildPage(null, $this->template);

        } catch (NotFoundException $e) {
            $this->title = _('Address');

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }

    public function post()
    {

        try {
            $this->user = Auth::user();

            // Load the user's profile
            $this->user->profiles->load();

            // Update the contact information for the user
            if (($result = $this->user->updateContact($_POST)) === true) {

                redirect(
                    action('\Project\Controllers\Account\Contact'),
                    array('notice' => _('Saved.'))
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Account\Contact'),
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
