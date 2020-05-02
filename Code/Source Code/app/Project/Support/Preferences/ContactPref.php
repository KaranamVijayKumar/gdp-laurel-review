<?php
/**
 * File: ContactPref.php
 * Created: 24-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Preferences;


use Story\DB;
use Story\Validator;
use Story\View;

class ContactPref implements PrefInterface
{
    /**
     * Converts the pref into a string
     *
     * @return string
     */
    public function __toString()
    {
        $view = new View('admin/preferences/partials/contact');

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

        foreach (array('contact_recipients') as $name) {
            $input[$name] = trim(html2text($input[$name]));
        }

        return $input;
    }

    /**
     * Adds the validation rules for the pref
     *
     * @param Validator $validator
     *
     * @param array $input
     * @return mixed
     */
    public function addValidationRules(Validator $validator, array $input)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $validator::addRule(
            'cse',
            function ($field, $value, array $params) {
                // explode the values
                $exploded = explode(',', $value);
                array_walk_recursive($exploded, 'trim');
                array_walk_recursive($exploded, 'html2text');

                foreach ($exploded as $email) {
                    $email = trim($email);
                    if (filter_var($email, \FILTER_VALIDATE_EMAIL) === false) {
                        return false;
                    }
                }
                return true;
            },
            _('Recipients must be comma separated email addresses.')
        );

        $validator->rule('required', 'contact_recipients');
        $validator->rule('cse', 'contact_recipients');
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
        $DB->update('config', array('value' => $input['contact_recipients']), array('name' => 'contact_recipients'));
    }
}
