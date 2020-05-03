<?php
/**
 * File: UserRole.php
 * Created: 05-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Error;
use Story\ORM;

/**
 * Class UserRole
 *
 * @package Project\Models
 */
class UserRole extends ORM
{

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'user_roles';

    /**
     * Saves the roles for a specific user
     *
     * @param User  $user
     * @param array $roles
     *
     * @return bool
     */
    public static function updateForUser(User $user, array $roles)
    {

        $roles = array_intersect($roles, Role::lists('id'));

        try {

            $db = static::$db;
            static::$db->pdo->beginTransaction();

            // remove existing user_roles for the user
            static::$db->delete(
                "DELETE FROM {$db->i(static::$table)} WHERE {$db->i('user_id')} = ?",
                array($user->id)
            );

            // insert the new roles
            foreach ($roles as $role_id) {
                $role = new UserRole();
                $role->set(array('user_id' => $user->id, 'role_id' => $role_id));
                $role->save();
            }

            // Commit Transaction
            static::$db->pdo->commit();
            event('account.updated', array($user, _('Roles')));

            return true;

        } catch (\PDOException $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);

            return false;
        }
    }
}
