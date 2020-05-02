<?php
/**
 * File: SubscriptionPref.php
 * Created: 24-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Preferences;

use Story\DB;
use Story\Validator;
use Story\View;

class SubscriptionPref implements PrefInterface
{
    /**
     * Converts the pref into a string
     *
     * @return string
     */
    public function __toString()
    {

        $view = new View('admin/preferences/partials/subscriptions');

        return $view->__toString();
    }

    /**
     * Adds the modifies the input array
     *
     * @param array $input
     *
     * @return mixed
     */
    public function addFilter(array $input)
    {

        foreach (array('subscription_allow_renew_before') as $name) {
            $input[$name] = trim(html2text($input[$name]));
        }

        return $input;
    }

    /**
     * Adds the validation rules for the pref
     *
     * @param Validator $validator
     * @param array     $input
     *
     * @return mixed
     */
    public function addValidationRules(Validator $validator, array $input)
    {
        $validator->rule('required', 'subscription_allow_renew_before');
        $validator->rule('integer', 'subscription_allow_renew_before');

        $validator->rule('required', 'subscription_renew_notify_days');
        $validator->rule('integer', 'subscription_renew_notify_days.*');
    }

    /**
     * Attempts to save the prefs
     *
     * @param array $input
     * @param DB    $DB
     *
     * @return mixed
     */
    public function save(array $input, DB $DB)
    {


        $DB->update(
            'config',
            array('value' => json_encode($input['subscription_renew_notify_days'])),
            array('name' => 'subscription_renew_notify_days')
        );
        $DB->update(
            'config',
            array('value' => (int) $input['subscription_allow_renew_before']),
            array('name' => 'subscription_allow_renew_before')
        );

    }
}
