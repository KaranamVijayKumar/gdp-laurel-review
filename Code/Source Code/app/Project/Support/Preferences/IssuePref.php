<?php
/**
 * File: IssuePref.php
 * Created: 12-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Preferences;

use Story\DB;
use Story\Validator;
use Story\View;

class IssuePref implements PrefInterface
{
    /**
     * Converts the pref into a string
     *
     * @return string
     */
    public function __toString()
    {

        $view = new View('admin/preferences/partials/issues');

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

        $decimal_sep = get_locale_info('decimal_point');
        $thousands_sep = get_locale_info('thousands_sep');
        foreach (array('latest_issue_price', 'back_issue_price', 'issue_tax') as $name) {
            $value = trim(html2text($input[$name]));
            $input[$name] = number_format($value, 2, $decimal_sep, $thousands_sep);
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

        foreach (array('latest_issue_price', 'back_issue_price') as $name) {
            $validator->rule('required', $name);
            $validator->rule('min', $name, 0.01);
            $validator->rule('max', $name, 1000000000);
        }

        $validator->rule('min', 'issue_tax', 0);
        $validator->rule('max', 'issue_tax', 100);
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

        foreach (array('latest_issue_price', 'back_issue_price', 'issue_tax') as $name) {

            $DB->update(
                'config',
                array('value' => $input[$name]),
                array('name' => $name)
            );

        }

    }
}
