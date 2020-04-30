<?php
/**
 * File: ContactController.php
 * Created: 11-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Project\Models\Profile;
use Project\Models\User;
use Project\Support\Contact\ContactSender;
use Story\Auth;
use Story\Error;
use Story\Validator;
use StorySpamProtector\Assessors\EmailAssessor;
use StorySpamProtector\SpamProtector;

class ContactController extends AbstractPage
{
    /**
     * Contact page name in the pages tbl
     */
    const PAGE_NAME = 'contact';

    /**
     * @var Profile
     */
    public $default;

    /**
     * @var SpamProtector
     */
    public $sp;

    /**
     * @var User
     */
    public $user;

    /**
     * Displays the contact form
     *
     */
    public function get()
    {

        try {

            $this->buildPageWithFallback(
                array(
                    'title' => _('Contact Us'),
                    'content' => _('In order to contact us, please fill out the following form:')
                ),
                'pages/contact'
            );

            // If the user is signed in we auto-populate their data
            $this->user = Auth::user();

            if (!$this->user) {
                $this->user = new User();
                $this->user->set(array('email' => ''));
                $this->default = new Profile;
                $this->default->set(array('value' => ''));
            }

            $this->sp = app('spamprotector');

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    public function post()
    {

        $input = $_POST;
        array_walk_recursive($input, 'trim');

        array_walk_recursive($input, 'html2text');
        $v = new Validator($input);

        $this->addCustomRules();

        // Email
        $v->rule('required', 'email');
        $v->rule('email', 'email');
        $v->rule('max', 'email', 200);
        $v->rule('blacklisted', 'email');

        // name
        $v->rule('required', 'name');
        $v->rule('max', 'name', 200);

        // message
        $v->rule('required', 'message');
        $v->rule('max', 'message', 65000);

        if (!Auth::check()) {
            // spam protection
            $v->rule('required', 'sp-response')->message(_('Spam protection field is required.'));
            $v->rule('sp', 'sp-response', $input);
        }


        if ($v->validate()) {

            $sender = new ContactSender($input['email'], $input['name'], $input['message']);

            $sender->send();

            // clear the fields
            app('session')->remove('__fields');

            // redirect to contact page with success msg
            redirect(
                action('\Project\Controllers\ContactController'),
                array(
                    'notice'      => _('Message sent successfully.'),
                )
            );
        }

        // if errors we display them
        redirect(
            action('\Project\Controllers\ContactController'),
            array(
                'error'      => $v->errorsToNotification(),
            )
        );
    }

    /**
     * Extends the validator with the custom rules
     *
     */
    private function addCustomRules()
    {
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
                        'response' => $value
                    )
                );

                return $sp->check('field');

            },
            _('The Spam protection is invalid.')
        );
    }
}
