<?php
/**
 * File: ResetPassword.php
 * Created: 27-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Account;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\PasswordReminder;
use Project\Models\User;
use Project\Support\PasswordReminder\InvalidPasswordReminderException;
use Project\Support\PasswordReminder\InvalidPasswordReminderFormException;
use Story\Auth;

use Story\Dispatch;
use Story\Error;
use Story\Validator;

class ResetPassword extends AdminBaseController
{
    /**
     * @var string
     */
    public $template = 'admin/account/reset_password';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $token;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        // if user is signed in we redirect to the account page
        if (Auth::check()) {
            redirect(
                action('\Project\Controllers\Admin\Account\Dashboard'),
                array('notice' => _('Already signed in.'))
            );
        }

        return parent::__construct($route, $dispatch);
    }


    /**
     * @param string $token
     */
    public function get($token = '')
    {

        $this->title = _('Reset Account Password');

        try {
            $token = trim(html2text($token));

            // validate the token
            $v = new Validator(compact('token'));

            $v->rule('required', 'token');
            $v->rule('lengthMax', 'token', PasswordReminder::TOKEN_LENGTH);

            $v->rule(
                'exists',
                'token',
                PasswordReminder::getTable(),
                'token',
                'created',
                '>',
                (time() - PasswordReminder::EXPIRES)
            );

            if (!$v->validate()) {
                redirect(
                    action('\Project\Controllers\Admin\Auth'),
                    array('error' => _('Invalid token supplied or token expired.'), '__fields' => array())
                );
            }

            $this->token = $token;

        } catch (\Exception $e) {
            if (config('debug')) {
                Error::exception($e);
            }
            redirect(
                action('\Project\Controllers\Admin\Account\Forgot'),
                array('error' => 'An error occurred processing your request.')
            );
        }
    }

    /**
     * @param string $token
     */
    public function post($token = '')
    {

        try {

            if (User::changePasswordWithToken($_POST, $token)) {
                redirect(
                    action('\Project\Controllers\Admin\Auth'),
                    array('notice' => _('Password changed.'), '__fields' => array())
                );
            }

        } catch (InvalidPasswordReminderException $e) {
            redirect(
                action('\Project\Controllers\Admin\Auth'),
                array('error' => _($e->getMessage()), '__fields' => array())
            );
        } catch (InvalidPasswordReminderFormException $e) {

            redirect(
                action('\Project\Controllers\Admin\Account\ResetPassword', array($token)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $e->getMessage(),
                )
            );

        } catch (\Exception $e) {
            if (config('debug')) {
                Error::exception($e);
            }
            redirect(
                action('\Project\Controllers\Admin\Account\Forgot'),
                array('error' => 'An error occurred processing your request.')
            );
        }

    }
}
