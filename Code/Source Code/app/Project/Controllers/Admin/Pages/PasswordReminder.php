<?php
/**
 * File: PasswordReminder.php
 * Created: 27-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class PasswordReminder
 *
 * @package Project\Models
 */
class PasswordReminder extends ORM
{

    /**
     * Password reminder expiration in seconds (Default: 2 hours)
     */
    const EXPIRES = 7200;

    /**
     * Token length
     */
    const TOKEN_LENGTH = 32;

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'password_reminders';

    /**
     * @param User $user
     *
     * @return PasswordReminder
     */
    public static function createTokenForUser(User $user)
    {

        $tbl = static::$table;
        $db = static::$db;
        // delete all other password reminders for the user
        $sql = "DELETE FROM {$tbl} WHERE {$db->i('email')} = ?";
        static::$db->delete($sql, array($user->email));

        $model = new static;

        $model->set(
            array(
                'email'   => $user->email,
                'token'   => random(static::TOKEN_LENGTH),
                'created' => time()
            )
        );

        return $model->save();
    }

    /**
     * Deletes the reminder entry from the db
     *
     * @return int
     */
    public function deleteReminder()
    {
        $db = static::$db;
        $tbl = static::getTable();
        $sql = "DELETE FROM {$tbl} WHERE {$db->i('token')} = ?";
        return static::$db->delete($sql, array($this->token));
    }
}
