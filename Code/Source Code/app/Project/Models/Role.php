<?php
/**
 * File: Role.php
 * Created: 05-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\ORM;

/**
 * Class Role
 *
 * @package Project\Models
 */
class Role extends ORM
{

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'roles';

    /**
     * List the roles sorted by name asc
     *
     * @param int $current
     * @param int $per_page
     *
     * @return array
     */
    public static function listRoles($current, $per_page)
    {

        $offset = $per_page * ($current - 1);

        $roles = static::$db->i(static::$table);
        $order = static::$db->i('order');

        // 1. get the items
        $sql = "SELECT * FROM {$roles} ORDER BY {$order} ASC LIMIT {$offset}, {$per_page}";
        $items = new Collection(static::$db->fetch($sql));

        // 2. count the total items
        $countSql = static::$db->select('COUNT(*)', static::$table);
        $total = static::$db->column($countSql[0], $countSql[1]);

        // 3. return the items
        return array('total' => $total, 'items' => $items);
    }

    /**
     * List the roles matching the query and sort by name
     *
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     *
     * @return array
     */
    public static function listRolesByQuery($query, $current, $per_page)
    {

        $offset = $per_page * ($current - 1);

        $roles = static::$db->i(static::$table);
        $name = static::$db->i('name');

        // 1. get the items
        $where = query_to_where($query, array('name'), static::$db->i);
        $sql = "SELECT * FROM {$roles} WHERE {$where['sql']} ORDER BY {$name} ASC LIMIT {$offset}, {$per_page}";
        $items = static::$db->fetch($sql, $where['values']);

        // 2. count the total items
        $countSql = "SELECT COUNT(*) FROM {$roles} WHERE {$where['sql']}";
        $count = static::$db->column($countSql, $where['values']);


        // 10. Return the values
        return array('total' => $count, 'items' => $items);
    }

    /**
     * Permissions accessor
     *
     * @param $value
     *
     * @return mixed
     */
    public function getPermissionsAttribute($value)
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
    public function setPermissionsAttribute($value)
    {

        return $this->attributes['permissions'] = json_encode(array_values($value));
    }
}
