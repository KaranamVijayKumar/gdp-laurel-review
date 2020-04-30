<?php
/**
 * File: PreferencesFactory.php
 * Created: 28-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

use Project\Support\Preferences\PrefInterface;
use Story\Error;
use Story\Validator;

/**
 * Class PreferencesFactory
 * Creates the preferences sections
 *
 * @package Project\Support
 */
class PreferencesFactory
{
    /**
     * Preferences order. Include all preferences in this array!
     *
     * @var array
     */
    public $order = array('SubscriptionPref', 'IssuePref', 'ChapbookPref', 'ContactPref', 'MailPref', 'SmtpPref');
    /**
     * Preferences modules
     *
     * @var array
     */
    protected $preferences = array();

    /**
     * Constructor
     */
    public function __construct()
    {

        // get all the prefs classes from the preferences dir and add them to the preferences
        foreach (glob(__DIR__ . '/Preferences/*Pref.php') as $key) {
            $name = 'Project\Support\Preferences\\' . pathinfo($key, PATHINFO_FILENAME);
            $key = basename($key, '.php');
            $this->preferences[$key] = new $name;
        }

        // reorder the preferences
        $order = $this->order;
        uksort($this->preferences, function ($key1, $key2) use ($order) {
            return (array_search($key1, $order) > array_search($key2, $order));
        });
        $this->preferences = array_values($this->preferences);
    }


    /**
     * Returns the preferences
     *
     * @return array
     */
    public function get()
    {

        return $this->preferences;
    }


    /**
     * Set the preferences
     *
     * @return bool|Validator
     */
    public function set()
    {

        $input = $_POST;

        /** @var PrefInterface $preference */
        foreach ($this->preferences as $preference) {
            // apply the filters to the input
            $input = $preference->addFilter($input);
        }

        $validation = new Validator($input);

        foreach ($this->preferences as $preference) {
            // validation rules
            $preference->addValidationRules($validation, $input);
        }

        if ($validation->validate()) {
            $db = load_database();
            try {

                $db->pdo->beginTransaction();

                // save the preferences
                foreach ($this->preferences as $preference) {
                    // validation rules
                    $preference->save($input, $db);
                }

                $db->pdo->commit();
                // redirect to pref page with saved message
                redirect(action('\Project\Controllers\Admin\Preferences\Index'), array('notice' => 'Saved.'));
            } catch (\Exception $e) {
                $db->pdo->rollBack();
                Error::exception($e);
            }

            return true;
        }

        return $validation;
    }
}
