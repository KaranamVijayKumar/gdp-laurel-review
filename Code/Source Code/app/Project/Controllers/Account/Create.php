<?php
/**
 * File: Create.php
 * Created: 19-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Account;

use Exception;

use Project\Controllers\AbstractPage;


use Project\Models\Profile;
use Project\Models\Role;
use Project\Models\User;
use Project\Models\UserRole;
use Story\Auth;
use Story\Cipher;

use Story\Dispatch;
use Story\Error;
use Story\Validator;

use StorySpamProtector\Assessors\EmailAssessor;
use StorySpamProtector\SpamProtector;

/**
 * Class Create
 *
 * @package Project\Controllers\Account
 */
class Create extends AbstractPage
{
    /**
     * Contact page name in the pages tbl
     */
    const PAGE_NAME = 'account/create';

    public $template = 'account/create';
    /**
     * @var SpamProtector
     */
    public $sp;

    /**
     * Constructor
     *
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        // if signed in we redirect to the user's account page with already signed in message
        if (Auth::check()) {
            redirect(action('\Project\Controllers\Account\Dashboard'), array('notice' => _('Already signed in.')));
        }


        // we check if we have access to account creation, otherwise we redirect to the sign in page
        // with the message that account creation is disabled
        if (!has_access('account_create')) {

            redirect(
                action('\Project\Controllers\Auth'),
                array('error' => _('Account creation is temporarily disabled.'))
            );
        }

        require_once SP . 'Project/Support/Events/account_events.php';
        return parent::__construct($route, $dispatch);
    }

    public function get()
    {

        try {

            $this->buildPageWithFallback(
                array(
                    'title' => _('Create account'),
                    'content' => '<p>' . sprintf(_('Create a %s account.'), 'Laurel Review') . '</p>'
                ),
                $this->template,
                null
            );

            $this->sp = app('spamprotector');

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }

    public function post()
    {

        $input = $_POST;
        $pass = $input['password'];
        $pass2 = $input['verify_password'];

        array_walk_recursive($input, 'trim');
        array_walk_recursive($input, 'html2text');

        $input['password'] = $pass;
        $input['verify_password'] = $pass2;

        $v = $this->createValidator($input);

        if ($v->validate()) {
            $db = load_database();


            try {
                $db->pdo->beginTransaction();

                // create the user and their profile, and set the roles
                $user = $this->createUser($input);

                // commit
                $db->pdo->commit();

                event('account.created_inactive', $user);

                redirect(
                    action('\Project\Controllers\Auth'),
                    array(
                        'notice' => _(
                            'Your account was created. You should receive the account activation email soon.'
                        ),
                        '__fields' => array()
                    )
                );
            } catch (Exception $e) {
                $db->pdo->rollBack();
                Error::exception($e);
            }
        }

        // if errors we display them
        redirect(
            action('\Project\Controllers\Account\Create'),
            array(
                'error' => $v->errorsToNotification(),
            )
        );
    }


    /**
     * @param $input
     *
     * @return User
     */
    protected function createUser($input)
    {

        $user = new User();
        $user->set(
            array(
                'email'    => $input['email'],
                'password' => base64_encode(Cipher::encrypt($input['password'])),
                'active'   => 0,
                'activation_token' => random(32),
            )
        );

        $user->save();

        $profile = new Profile();
        $profile->set(
            array(
                'user_id' => $user->id,
                'name'    => 'name',
                'value'   => $input['name']
            )
        );
        $profile->save();

        // add the default roles for the user
        $default_roles = $this->getDefaultRoles();
        // set the roles
        foreach (array_keys($default_roles) as $id) {
            $role = new UserRole();
            $role->user_id = $user->id;
            $role->role_id = $id;
            $role->save();
        }

        return $user;
    }

    /**
     * @return array
     */
    protected function getDefaultRoles()
    {

        $project = app('project');
        $default_roles = Role::lists(
            'id',
            'name',
            array('id' => config('default_roles')),
            0,
            0,
            array('order' => 'asc')
        );
        return $default_roles;
    }

    /**
     * @param $input
     *
     * @return Validator
     */
    protected function createValidator($input)
    {

        $v = new Validator($input);

        // email blacklisted
        /** @noinspection PhpUnusedParameterInspection */
        Validator::addRule(
            'blacklisted',
            function ($field, $value) {

                /** @var SpamProtector $sp */
                $sp = app('spamprotector');
                EmailAssessor::$attribute_value = $value;

                return $sp->check('email');
            },
            _('Email address is blacklisted.')
        );

        // spam protector field
        /** @noinspection PhpUnusedParameterInspection */
        Validator::addRule(
            'sp',
            function ($field, $value, array $params) {

                /** @var SpamProtector $sp */
                $sp = app('spamprotector');
                $input = $params[0];

                $sp->getFieldAssessor()->setData(
                    array(
                        'challenge' => $input['sp-challenge'],
                        'response'  => $value
                    )
                );

                return $sp->check('field');

            },
            _('The Spam protection is invalid.')
        );

        // name
        $v->rule('required', 'name');
        $v->rule('max', 'name', 200);

        // Email
        $v->rule('required', 'email');
        $v->rule('email', 'email');
        $v->rule('max', 'email', 200);
        $v->rule('unique', 'email', 'users', 'email', $input['email'])
            ->message(_('{field} is taken.'));
        $v->rule('blacklisted', 'email');

        // password
        $v->rule('required', 'password');
        $v->rule('lengthMin', 'password', 8);
        $v->rule('lengthMax', 'password', 200);

        $v->rule('required', 'verify_password');
        $v->rule('equals', 'verify_password', 'password');

        // spam protection
        $v->rule('required', 'sp-response')->message(_('Spam protection field is required.'));
        $v->rule('sp', 'sp-response', $input);

        // terms
        $v->rule('required', 'agree')
            ->message('Terms and conditions must be accepted.');
        $v->rule('accepted', 'agree')
            ->message('Terms and conditions must be accepted.');
        return $v;
    }
}
