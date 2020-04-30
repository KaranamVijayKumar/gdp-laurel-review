<?php
/**
 * File: LogFactory.php
 * Created: 04-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

use Project\Models\Log;
use Story\Collection;
use Story\HTML;
use Story\ORM;

/**
 * Class LogFactory
 * @package Project\Support
 */
class LogFactory
{

    /**
     * @var Collection
     */
    public $log_collection;

    /**
     * @var \Story\DB
     */
    protected $database;

    /**
     * @var callable
     */
    protected $id_callback;

    /**
     * @param Collection $log_collection
     * @param callable|null $id_callback
     */
    public function __construct(Collection $log_collection, $id_callback = null)
    {

        $this->log_collection = $log_collection;
        $this->id_callback = $id_callback;

        $this->database = load_database();
    }

    /**
     * @param Collection $log_collection
     * @param null|callable $id_callback
     * @return Collection
     */
    public static function get(Collection $log_collection, $id_callback = null)
    {
        $factory = new static($log_collection, $id_callback);

        return $factory->build();
    }

    /**
     * Gets and builds the logs for the model
     *
     * @param ORM $model
     * @param null|callable $id_callback
     * @param int $limit
     * @param int $offset
     * @param null $order
     * @return Collection
     */
    public static function model(ORM $model, $id_callback = null, $limit = 0, $offset = 0, $order = null)
    {

        return static::get(Log::many($model, null, $limit, $offset, $order), $id_callback);
    }

    /**
     * Builds the current logs
     *
     * @return Collection
     */
    public function build()
    {
        // get the users for the logs
        $user_ids = array();
        $payloads = array();

        foreach ($this->log_collection as $log) {

            $payloads[$log->id] = json_decode($log->attributes['payload']);


            if (!isset($payloads[$log->id]->user) || !$payloads[$log->id]->user) {
                continue;
            }

            $user_ids[] = $payloads[$log->id]->user;
        }

        $user_ids = array_unique($user_ids);

        $profiles = $this->getUserProfileNames($user_ids);

        // start to generate the human readable logs
        $has_user_view_access = has_access('admin_users_edit');
        foreach ($this->log_collection as $log) {
            $msg = $log->message;
            // do we have a payload? if so we replace the replacements in the message with the payload values
            $msg = $this->replaceMessageKeys($payloads, $log, $profiles, $has_user_view_access, $msg);
            $log->built_message = $msg;
        }

        return $this->log_collection;
    }

    /**
     * Replaces the message keys with the payload values
     *
     * @param array $payloads
     * @param \stdClass|Log $log
     * @param Collection $profiles
     * @param bool $has_user_view_access
     * @param string $msg
     *
     * @return mixed
     */
    protected function replaceMessageKeys($payloads, $log, $profiles, $has_user_view_access, $msg)
    {

        if (isset($payloads[$log->id])) {

            foreach ($payloads[$log->id] as $key => $value) {

                if (ends_with($key, '_fallback')) {
                    continue;
                }


                // if the key = user we try to search in the profile or use the fallback
                if ($key === 'user') {
                    $replacement = $this->replaceUser($payloads, $log, $profiles, $has_user_view_access, $value, $key);

                } elseif ($key === 'id' && $this->id_callback) {


                    $replacement = call_user_func($this->id_callback, $log);

                } else {
                    $replacement = '<strong>' . _($value) . '</strong>';
                }

                $msg = str_replace('{' . $key . '}', $replacement, $msg);

            }

            return $msg;
        }

        return $msg;
    }

    /**
     * Replaces the user id with the user's name or liked user's name
     *
     * @param array $payloads
     * @param \stdClass|Log $log
     * @param Collection $profiles
     * @param bool $has_user_view_access
     * @param string $value
     * @param string $key
     *
     * @return string
     */
    protected function replaceUser($payloads, $log, $profiles, $has_user_view_access, $value, $key)
    {

        $replacement = $profiles->findBy('user_id', $value);
        if ($replacement) {
            $replace = $replacement->value;
        } else {
            $fallback_key = $key . '_fallback';
            $replace = isset($payloads[$log->id]->$fallback_key) ?
                $payloads[$log->id]->$fallback_key : $value;
        }

        // do we have access to view the user?
        if ($has_user_view_access && $replacement) {
            $replacement = HTML::link(
                action('\Project\Controllers\Admin\Users\Edit', array($value)),
                $replace
            );

            return $replacement;
        } else {
            $replacement = '<strong>' . $replace . '</strong>';

            return $replacement;
        }
    }

    /**
     * @param array $user_ids
     *
     * @return Collection
     */
    protected function getUserProfileNames(array $user_ids)
    {

        if (count($user_ids)) {
            $idPlaceholders = trim(str_repeat('?,', count($user_ids)), ',');
            // get the users
            $i = $this->database->i;
            $sql = ("SELECT {$i}user_id{$i},{$i}name{$i},{$i}value{$i} " .
                "FROM {$i}profiles{$i} WHERE {$i}name{$i} = ? AND {$i}user_id{$i} IN ({$idPlaceholders})");

            return new Collection($this->database->fetch($sql, array_merge(array('name'), $user_ids)));

        }

        return new Collection();
    }
}
