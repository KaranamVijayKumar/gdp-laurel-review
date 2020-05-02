<?php
/**
 * File: PrefInterface.php
 * Created: 28-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Preferences;

use Story\DB;
use Story\Validator;

interface PrefInterface
{
    /**
     * Converts the pref into a string
     *
     * @return string
     */
    public function __toString();

    /**
     * Adds the modifies the input array
     *
     * @param array $input
     *
     * @return mixed
     */
    public function addFilter(array $input);

    /**
     * Adds the validation rules for the pref
     *
     * @param Validator $validator
     * @param array $input
     *
     * @return mixed
     */
    public function addValidationRules(Validator $validator, array $input);

    /**
     * Attempts to save the prefs
     *
     * @param array $input
     * @param DB    $DB
     *
     * @return mixed
     */
    public function save(array $input, DB $DB);
}
