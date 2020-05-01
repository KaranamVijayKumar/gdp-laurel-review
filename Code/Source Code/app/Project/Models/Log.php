<?php
/**
 * File: Log.php
 * Created: 14-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\ORM;

/**
 * Class Log
 *
 * @package Project\Models
 */
class Log extends ORM
{

    /**
     * @var string
     */
    protected static $table = 'logs';

    /**
     * Built message
     *
     * @var string
     */
    public $built_message;
    /**
     * Creates a new log and saves it
     *
     * @param \stdClass|ORM $model
     * @param string $message
     * @param mixed $payload
     *
     * @return mixed
     */
    public static function create($model, $message, $payload)
    {

        $log = new static;
        $log->set(
            array(
                'loggable_id'   => $model->id,
                'loggable_type' => get_class($model),
                'message'       => $message,
                'payload'       => $payload
            )
        );

        return $log->save();
    }

    /**
     * Returns a collection of logs for  a model
     *
     * @param $model
     * @param null $loggable_id
     * @param int $limit
     * @param int $offset
     * @param null $order
     * @return Collection
     */
    public static function many($model, $loggable_id = null, $limit = 0, $offset = 0, $order = null)
    {
        if ($model instanceof ORM) {
            $loggable_type = get_class($model);
            $loggable_id = $model->id;
        } else {
            $loggable_type = $model;
        }
        $rows = static::all(
            array(
                'loggable_type' => $loggable_type,
                'loggable_id'   => $loggable_id
            ),
            $limit,
            $offset,
            $order
        );

        if ($rows) {
            foreach ($rows as $key => $row) {
                $rows[$key] = new static($row);
            }
        }

        return new Collection($rows);
    }

    /**
     * Permissions accessor
     *
     * @param $value
     *
     * @return mixed
     */
    public function getPayloadAttribute($value)
    {

        return json_decode($value, true);
    }

    /**
     * Permissions mutator
     *
     * @param $value
     *
     * @return string
     */
    public function setPayloadAttribute($value)
    {

        return $this->attributes['payload'] = json_encode($value);
    }
}
