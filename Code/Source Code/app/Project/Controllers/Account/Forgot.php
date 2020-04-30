<?php
/**
 * File: Forgot.php
 * Created: 11-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Account;

use Project\Controllers\AbstractPage;
use Project\Models\PasswordReminder;
use Project\Models\User;
use Story\Auth;
use Story\Dispatch;
use Story\Error;
use Story\Validator;

/**
 * Class Forgot
 *
 * @package Project\Controllers\Account
 */
class Forgot extends AbstractPage
{

    /**
     * @var string
     */
    public $template = 'account/forgot';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        // if user is signed in we redirect to the account page
        if (Auth::check()) {
            redirect(
                action('\Project\Controllers\Account\Dashboard'),
                array('notice' => _('Already signed in.'))
            );
        }

        return parent::__construct($route, $dispatch);
    }

    /**
     *
     */
    public function get()
    {
        try {
            $this->buildPageWithFallback(
                array(
                    'title'   => _('Reset Account Password'),
                    'content' => '<p>' .
                        _('Please provide the email address which is associated with the Laurel Review account.') .
                        '</p>'
                ),
                $this->template,
                null
            );
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
            $input = array_map('html2text', $_POST);
            $input = array_map('trim', $input);
            // Validate the email address against the db
            $validator = new Validator($input);

            $validator->rule('required', 'email');
            $validator->rule('email', 'email');
            $validator->rule('exists', 'email', 'users', 'email', 'active', '=', '1')
                ->message(_('Invalid E-mail address.'));

            if ($validator->validate()) {
                // get the user
                $user = User::one(array('email' => $input['email']));


                /** @noinspection PhpUndefinedFieldInspection */
                $user->reminder = PasswordReminder::createTokenForUser($user);
                // create a token and insert into the password reminders along with the timestamp

                // send email (account.forgot) template
                event('account.forgot', $user);

                redirect(
                    action('\Project\Controllers\Auth'),
                    array('notice' => _('Password reminder email sent.'), '__fields' => array())
                );
            }

            // if errors we display them
            redirect(
                action('\Project\Controllers\Account\Forgot'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $validator->errorsToNotification(),
                )
            );
        } catch (\Exception $e) {
            if (config('debug')) {
                Error::exception($e);
            }
            redirect(
                action('\Project\Controllers\Account\Forgot'),
                array('error' => 'An error occurred processing your request.')
            );
        }
    }
}
