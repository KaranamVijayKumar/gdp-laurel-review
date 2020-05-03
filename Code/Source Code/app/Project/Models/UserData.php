<?php
/**
 * File: UserData.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Auth;
use Story\Collection;
use Story\Error;
use Story\ORM;
use Story\URL;

/**
 * Class UserData
 *
 * @package Project\Models
 */
class UserData extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'user' => '\Project\Models\User',
    );

    public static $data = array();

    public static $isDataLoaded = false;

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'user_data';

    /**
     * Clears all the user data
     */
    public static function clearData()
    {

        static::$data = array();

        if (Auth::check()) {
            $user = Auth::user();
            $table = static::$db->i(static::$table);
            $user_id = static::$db->i('user_id');
            static::$db->delete("DELETE FROM {$table} WHERE {$user_id} = ?", array($user->id));
        }
    }

    /**
     * Redirect to the filtered url when the current url is passed without the parameters
     *
     * @param string $filterName
     * @param array  $params
     * @param array  $defaults
     * @param string $action
     * @param array  $action_params
     *
     * @throws \Exception
     */
    public static function redirectToFilter(
        $filterName,
        array $params,
        array $defaults,
        $action,
        array $action_params = array()
    ) {

        // we check if the params keys equals with the defaults keys
        if (array_keys($params) !== array_keys($defaults)) {
            throw new \Exception("\$params keys doesn't match the \$defaults keys.");
        }
        $session = app('session');
        // get the data
        $user_data = static::getData($filterName, array());
        // get the current url
        $current_url = URL::current();
        // get the base url
        $base_url = URL::action($action, $action_params);
        // When the request doesn't match the current url and we are not sending any $_GET params
        // we redirect the page to the url generated with the user data
        if (!count($_GET) && $base_url === $current_url) {
            // build the params
            $redirect_params = array();
            foreach (array_keys($params) as $name) {
                $redirect_params[] = isset($user_data[$name]) ? $user_data[$name] : $defaults[$name];
            }

            $url = action($action, $action_params + $redirect_params);
            // add the query if exists
            if (isset($user_data['query']) && $user_data['query']) {
                $url .= '?' . http_build_query($user_data['query']);
            }

            // keep the session notices and errors if any
            $session->keep('notice');
            $session->keep('error');

            // and redirect
            redirect($url);
        } else {
            // otherwise we save the filter in the db

            // When we have an ajax request, we send only $_GET params, so the
            // existing params must remain unchanged or set to default if not set
            if (AJAX_REQUEST) {
                foreach ($params as $name => $value) {
                    if (!isset($user_data[$name])) {
                        $user_data[$name] = $defaults[$name];
                    }
                }
            } else {
                // otherwise we update the params
                foreach ($params as $name => $value) {

                    $user_data[$name] = $params[$name];
                }
            }

            // remove the page count
            unset($_GET['page']);

            $user_data['query'] = array_filter($_GET);
            static::pushData($filterName, $user_data);
        }
    }

    /**
     * Returns a value for the user
     *
     * @param string $name
     * @param null   $default
     *
     * @return null|mixed
     */
    public static function getData($name, $default = null)
    {

        // load the data if needed
        if (!static::$isDataLoaded) {
            if (Auth::check()) {
                $user = Auth::user();
                $db_data = static::lists('name', 'value', array('user_id', $user->id));
                foreach ($db_data as $db_name => $db_value) {
                    $db_data[$db_name] = $db_data[$db_name] ? unserialize(
                        base64_decode($db_value)
                    ) : $db_data[$db_name];
                }

                static::$data = array_merge($db_data, static::$data);
            }
            static::$isDataLoaded = true;
        }

        if (isset(static::$data[$name])) {
            return static::$data[$name];
        }

        return $default;
    }

    /**
     * Pushes a value to the data array
     *
     * @param string $name
     * @param mixed  $data
     */
    public static function pushData($name, $data)
    {

        static::$data[$name] = $data;
    }

    /**
     * Saves the data into the db. This should be called on system.shutdown
     */
    public static function storeData()
    {

        if (!Auth::check() || !count(static::$data)) {
            return false;
        }

        $user = Auth::user();
        // get the collection of all the data by user_id and keys

        $items = new Collection(
            static::objects(
                '*',
                null,
                null,
                array(
                    'user_id' => $user->id,
                    static::$db->i('name') . ' IN ("' . implode('", "', array_keys(static::$data)) . '")'
                )
            )
        );

        try {
            static::$db->pdo->beginTransaction();

            foreach (static::$data as $name => $value) {
                $item = $items->findBy('name', $name);
                if (!$item) {
                    $item = new static;
                    $item->set(array('user_id' => $user->id, 'name' => $name));
                }

                $item->set(array('value' => base64_encode(serialize($value))));
                $item->save();
            }

            static::$db->pdo->commit();

            return true;

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }
}
