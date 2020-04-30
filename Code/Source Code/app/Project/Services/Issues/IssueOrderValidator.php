<?php
/**
 * File: IssueOrderValidator.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Issues;

use Story\Validator;
use StorySpamProtector\Assessors\EmailAssessor;
use StorySpamProtector\SpamProtector;

/**
 * Class IssueOrderValidator
 *
 * @package Project\Services\Issues
 */
class IssueOrderValidator extends Validator
{
    /**
     * Constructor
     *
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {


        $data = $this->filterData($data);

        parent::__construct($data, $fields);
    }

    /**
     * Filters the input data
     *
     * @param array $data
     *
     * @return array
     */
    protected function filterData(array $data)
    {

        $data = array_map('html2text', $data);
        $data = array_map('trim', $data);

        return $data;
    }

    /**
     * Add create order rules
     *
     * @internal param Issue $issue
     */
    public function addCreateOrderRules()
    {
//        // add custom rules
//        $this->addCustomRules();
//
//        // name
//        $this->rule('required', 'name');
//        $this->rule('lengthMax', 'name', 200);
//        // email
//        $this->rule('required', 'email');
//        $this->rule('email', 'email');
//        $this->rule('blacklisted', 'email');
//
//        // contact
//        $this->rule('required', 'address');
//        $this->rule('lengthMax', 'address', 200);
//
//        $this->rule('lengthMax', 'address2', 200);
//
//        $this->rule('required', 'city');
//        $this->rule('lengthMax', 'city', 200);
//
//        $this->rule('required', 'state');
//        $this->rule('lengthMax', 'state', 200);
//
//        $this->rule('required', 'zip');
//        $this->rule('lengthMax', 'zip', 200);
//
//        $this->rule('required', 'country');
//        $this->rule('lengthMax', 'country', 200);
//        /** @noinspection PhpUnusedParameterInspection */
//        $this->addRule(
//            'country',
//            function ($field, $value) {
//
//                return array_key_exists($value, require SP . 'config/countries.php');
//            },
//            _('Invalid {field}.')
//        );
//        $this->rule('country', 'country');
//
//        $this->rule('required', 'phone');
//        $this->rule('lengthMax', 'phone', 200);
//
//        // spam
//        if (!Auth::check()) {
//            // spam protection
//            $this->rule('required', 'sp-response')->message(_('Spam protection field is required.'));
//            $this->rule('sp', 'sp-response', $this->data());
//        }
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
