<?php
/**
 * File: Delete.php
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
use Story\Validator;

/**
 * Class Delete
 * @package Project\Controllers\Account
 */
class Delete extends AbstractPage
{
    /**
     * @var \stdClass
     */
    public $default;

    /**
     * @var string
     */
    public $template = 'account/delete';

    /**
     * @var string
     */
    public $title;

    /**
     * @var \Project\Models\User
     */
    public $user;

    /**
     * Shows the delete page
     */
    public function get()
    {

        try {

            $this->user = Auth::user();
            $this->buildPageWithFallback(
                array(
                    'title' => _('Delete your account'),
                    'content' => '<p>' . _('Please provide your account password to proceed.') . '</p>'
                ),
                $this->template
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
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

            redirect(action('\Project\Controllers\Auth'), array('notice' => 'Account deleted.'));
        }

        // if errors we display them
        redirect(
            action('\Project\Controllers\Account\Delete'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $validator->errorsToNotification(),
            )
        );
    }
}
