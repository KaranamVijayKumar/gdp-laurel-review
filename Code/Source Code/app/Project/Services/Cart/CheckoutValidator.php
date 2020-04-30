<?php
/**
 * File: CheckoutValidator.php
 * Created: 11-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Cart;

use Story\Auth;
use Story\Validator;
use StorySpamProtector\Assessors\EmailAssessor;
use StorySpamProtector\SpamProtector;

class CheckoutValidator extends Validator
{
    public function __construct($data, $fields = array())
    {
        $data = array_map('html2text', $data);
        $data = array_map('trim', $data);

        parent::__construct($data, $fields);
    }

    public static function notShippable($input)
    {
        $v = new static($input);

        return $v;
    }

    public static function shippable($input)
    {
        $v = new static($input);

        // add custom rules
        $v->addCustomRules();

        // name
        $v->rule('required', 'name');
        $v->rule('lengthMax', 'name', 200);
        // email
        $v->rule('required', 'email');
        $v->rule('email', 'email');
        $v->rule('blacklisted', 'email');

        // contact
        $v->rule('required', 'address');
        $v->rule('lengthMax', 'address', 200);

        $v->rule('lengthMax', 'address2', 200);

        $v->rule('required', 'city');
        $v->rule('lengthMax', 'city', 200);

        $v->rule('required', 'state');
        $v->rule('lengthMax', 'state', 200);

        $v->rule('required', 'zip');
        $v->rule('lengthMax', 'zip', 200);

        $v->rule('required', 'country');
        $v->rule('lengthMax', 'country', 200);
        /** @noinspection PhpUnusedParameterInspection */
        $v->addRule(
            'country',
            function ($field, $value) {

                return array_key_exists($value, require SP . 'config/countries.php');
            },
            _('Invalid {field}.')
        );
        $v->rule('country', 'country');

        $v->rule('required', 'phone');
        $v->rule('lengthMax', 'phone', 200);

        // spam
        if (!Auth::check()) {
            // spam protection
            $v->rule('required', 'sp-response')->message(_('Spam protection field is required.'));
            $v->rule('sp', 'sp-response', $v->data());
        }

        return $v;

    }

    /**
     * Add custom rules specific to this validator
     */
    protected function addCustomRules()
    {
        // email blacklisted
        /** @noinspection PhpUnusedParameterInspection */
        static::addRule(
            'blacklisted',
            function ($field, $value, array $params) {

                /** @var SpamProtector $sp */
                $sp = app('spamprotector');
                EmailAssessor::$attribute_value = $value;

                return $sp->check('email');
            },
            _('Email address is blacklisted.')
        );

        // spam protector field
        /** @noinspection PhpUnusedParameterInspection */
        static::addRule(
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
