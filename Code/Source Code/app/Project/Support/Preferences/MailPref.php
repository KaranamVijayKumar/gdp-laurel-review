<?php
/**
 * File: MailPref.php
 * Created: 28-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Preferences;

use Story\DB;
use Story\Validator;
use Story\View;

class MailPref implements PrefInterface
{
    /**
     * Converts the pref into a string
     *
     * @return string
     */
    public function __toString()
    {
        $view = new View('admin/preferences/partials/mail');

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

        foreach (array('mail_from', 'mail_from_name') as $name) {
            $input[$name] = html2text($input[$name]);
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
        $validator->rule('required', 'mail_from')->message('Sender\'s e-mail address required.');
        $validator->rule('email', 'mail_from')->message('Sender\'s e-mail is invalid.');
        $validator->rule('lengthMax', 'mail_from', 200)
            ->message('Sender\'s e-mail address cannot be more than 200 characters.');

        $validator->rule('required', 'mail_from_name')->message('Sender\'s name required.');
        $validator->rule('lengthMax', 'mail_from_name', 200)
            ->message('Sender\'s name cannot be more than 200 characters.');
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
        $DB->update('config', array('value' => $input['mail_from']), array('name' => 'mail_from'));
        $DB->update('config', array('value' => $input['mail_from_name']), array('name' => 'mail_from_name'));
    }
}
