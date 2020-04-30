<?php
/**
 * File: Roles.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

use Project\Models\Role;
use Story\Auth;
use Story\Collection;
use Story\Form;
use Story\HTML;

/**
 * Class Roles
 * Handles the user roles
 *
 * @package Project\Support
 */
class Roles
{

    /**
     * All permissions keyword
     */
    const ALL_PERMISSIONS = '__ALL__';

    /**
     * @var array
     */
    public static $deleteMethods = array('delete');

    /**
     * @var array
     */
    public static $manageMethods = array('post', 'put', 'patch');

    /**
     * @var bool|array
     */
    protected static $permissions = false;


    /**
     * @param array $selected
     * @param array $permissionArray
     *
     * @return Collection
     */
    public static function getAllPermissions($selected = array(), array $permissionArray = null)
    {

        if ($permissionArray === null) {
            $permissionArray = new \RecursiveArrayIterator(require SP . 'config/role_categories.php');
        }

        // build the flattened array that will be inserted as a template
        $items = new Collection();
        iterator_apply(
            $permissionArray,
            'Project\Support\Roles::traverseStructure',
            array($permissionArray, $items, $selected)
        );


        return $items;
    }

    /**
     * Returns the role permission name based on method and controller name
     *
     * @param string $method
     * @param string $controller
     * @param string $prefix
     *
     * @return string
     */
    public static function getPermissionName($method, $controller, $prefix = 'Project\Controllers\\')
    {

        $suffix = '';
        // get the correct method and function
        if ($method !== 'get' &&
            !in_array($method, static::$manageMethods) &&
            !in_array($method, static::$deleteMethods)) {

            $all_methods = array_merge(array('get'), static::$manageMethods, static::$deleteMethods);

            foreach ($all_methods as $default_method) {
                if (strpos($method, $default_method) === 0) {
                    $suffix = '_' . strtolower(substr($method, strlen($default_method)));
                    $method = $default_method;
                }
            }
        }

        if (in_array($method, static::$manageMethods)) {
            $name = 'manage_';
        } elseif (in_array($method, static::$deleteMethods)) {
            $name = 'delete_';
        } else {
            $name = '';
        }


        $pos = strpos($controller, $prefix);

        if ($pos !== false) {

            $controller = substr_replace($controller, '', $pos, strlen($prefix));
        }

        $name = $name . slug(str_replace('\\', '-', $controller . $suffix), '_');

        return $name;
    }

    /**
     * Check access for the current name
     *
     * @param $name
     *
     * @return bool
     */
    public static function hasAccess($name)
    {

        if (static::$permissions === false) {

            $roles = new Collection();

            if (Auth::check() && ($user = Auth::user())) {
                /** @noinspection PhpUndefinedMethodInspection */
                $roles = $user->roles()->load();
            }

            // get the default role (guest) and add it to the roles
            $defaultRole = Role::one(array('default' => '1'));

            if ($defaultRole) {
                $roles->push($defaultRole);

            }

            // merge all the roles
            static::$permissions = array();
            foreach ($roles as $role) {
                $permissionArray = $role->permissions;


                if (is_array($permissionArray)) {
                    static::$permissions = array_merge(static::$permissions, $permissionArray);
                }
            }
        }

        return in_array($name, static::$permissions) || in_array(self::ALL_PERMISSIONS, static::$permissions);
    }

    /**
     * @param \RecursiveArrayIterator $iterator
     * @param Collection              $items
     * @param array                   $selected
     * @param int                     $heading
     */
    protected static function traverseStructure(
        \RecursiveArrayIterator $iterator,
        Collection $items,
        array $selected = null,
        $heading = 4
    ) {

        while ($iterator->valid()) {

            if ($iterator->hasChildren()) {

                if ($heading === 4) {
                    $attributes = array('class' => 'content-hero i--header orange');
                } else {
                    $attributes = array('class' => 'content-hero--small');
                }
                $items->push(
                    '<h' . $heading . HTML::attributes($attributes) . '> ' . $iterator->key() . '</h' . $heading . '>'
                );

                static::traverseStructure($iterator->getChildren(), $items, $selected, $heading + 1);

            } else {
                $name = slug($iterator->key());

                $permissionNames = explode(',', $iterator->key());
                $permissionNames = array_map('trim', $permissionNames);

                if (in_array(static::ALL_PERMISSIONS, (array)$selected)) {
                    $itemChecked = true;
                } else {
                    $count = 0;
                    foreach ($permissionNames as $name) {
                        if (in_array($name, $selected)) {
                            $count++;
                        }
                    }
                    $itemChecked = (count($permissionNames) === $count);
                }

                $item = array(
                    Form::checkbox('permissions[]', $iterator->key(), $itemChecked, array('id' => $name)),
                    Form::label($name, $iterator->current())
                );
                $items->push($item);
            }

            $iterator->next();
        }
    }
}
