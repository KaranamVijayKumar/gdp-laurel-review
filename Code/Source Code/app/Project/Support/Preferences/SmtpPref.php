<?php
/**
 * File: Smtp.php
 * Created: 28-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Preferences;

use Story\Cipher;
use Story\DB;
use Story\Validator;
use Story\View;

class SmtpPref implements PrefInterface
{

    /**
     * Converts the pref into a string
     *
     * @return string
     */
    public function __toString()
    {

        $view = new View('admin/preferences/partials/smtp');

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

        $array = array('smtp', 'smtp_host', 'smtp_auth', 'smtp_username', 'smtp_password', 'smtp_secure', 'smtp_port');
        foreach ($array as $name) {
            $input[$name] = html2text($input[$name]);
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

        $validator->rule('in', 'smtp', array('0', '1'))->message(_('Invalid SMTP.'));
        $validator->rule('in', 'smtp_secure', array('0', 'tls', 'ssl'))->message(_('Invalid Encryption.'));
        $validator->rule('integer', 'smtp_port')->message(_('Port must be integer.'));
        $validator->rule('min', 'smtp_port', 1)->message(_('Port cannot be smaller then 1.'));
        $validator->rule('max', 'smtp_port', 65535)->message(_('Port cannot be larger then 65535.'));

        // if smtp is set we require the host
        if ($input['smtp']) {
            $validator->rule('required', 'smtp_host')->message('SMTP servers required.');
            $validator->rule('required', 'smtp_port')->message('Port is required.');
            $validator->rule('lengthMax', 'smtp_host', 200)
                ->message('SMTP servers cannot be more than 200 characters.');
        }

        $validator->rule('in', 'smtp_auth', array('0', '1'))->message(_('Invalid SMTP Authentication.'));

        // if auth required username is required
        if ($input['smtp_auth']) {
            $validator->rule('required', 'smtp_username')->message('SMTP Username is required.');
            $validator->rule('lengthMax', 'smtp_username', 200)
                ->message('SMTP Username cannot be more than 200 characters.');

            $validator->rule('lengthMax', 'smtp_password', 200)
                ->message('SMTP Password cannot be more than 200 characters.');
        }

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

        $DB->update('config', array('value' => $input['smtp']), array('name' => 'smtp'));
        $DB->update('config', array('value' => $input['smtp_host']), array('name' => 'smtp_host'));
        $DB->update('config', array('value' => $input['smtp_auth']), array('name' => 'smtp_auth'));
        $DB->update('config', array('value' => $input['smtp_username']), array('name' => 'smtp_username'));
        $DB->update('config', array('value' => $input['smtp_secure']), array('name' => 'smtp_secure'));
        $DB->update('config', array('value' => $input['smtp_port']), array('name' => 'smtp_port'));
        if ($input['smtp_password']) {
            $DB->update(
                'config',
                array('value' => base64_encode(Cipher::encrypt($input['smtp_password']))),
                array('name' => 'smtp_password')
            );
        }
    }
}
