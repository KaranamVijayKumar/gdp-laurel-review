<?php
/**
 * File: MenuFactory.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

use Project\Models\Menu;
use Project\Support\Iterators\ListDecorator;
use Project\Support\Iterators\RecursiveListIterator;
use Project\Support\Iterators\TreeNode;
use Project\Support\Iterators\TreeNodesIterator;
use RecursiveArrayIterator;
use Story\Error;
use Story\HTML;

/**
 * Class MenuFactory
 * Creates a menu
 *
 * @package Project\Support
 */
class MenuFactory
{
    /**
     * @var bool|RecursiveArrayIterator
     */
    public static $menus = false;

    /**
     * Returns the menu
     *
     * @param       $name
     * @param array $selected
     * @param array $attributes
     *
     * @return bool|string
     */
    public static function get($name, $selected = array(), array $attributes = array())
    {
        if (static::$menus === false) {
            static::setMenus();
        }


        if (isset(static::$menus[$name]) && is_array(static::$menus[$name])) {
            return static::buildMenu(static::$menus[$name], $selected, $attributes);
        }

        return false;
    }

    /**
     * Generates from menu repository collection
     *
     * @param array $menus
     * @return array
     */
    public static function generateFromRepository(array $menus)
    {

        $refs = array();
        $list = array();

        reset($menus);
        /** @noinspection PhpUnusedLocalVariableInspection */
        while (list($k, $data) = each($menus)) {
            $thisref = &$refs[ $data->id ];

            $thisref['parent_id'] = $data->parent_id;

            $thisref['id'] = $data->item_id;
            $thisref['__model'] = $data;
            $thisref['name'] = static::generateName($data);

            if ($data->access) {
                $thisref['access'] = $data->access;
            }

            if ($data->parent_id == 0) {
                $list[$data->menu_name][ $data->id ] = &$thisref;
            } else {
                $refs[ $data->parent_id ]['children'][ $data->id ] = &$thisref;
            }
        }

        return $list;

    }
    /**
     * Sets all the menus and merges from the repo also
     *
     */
    public static function setMenus()
    {
        if (static::$menus === false) {

            // Get the menus from the db
            $repo_menus = Menu::allCached(array('status' => 1), 0, 0, array('order' => 'asc'));

            $repo_menus = static::generateFromRepository((array) $repo_menus);

            static::$menus = array_merge_recursive(require SP . 'config/menus.php', $repo_menus);
        }

    }

    /**
     * Adds menus to the menu repo
     *
     * @param $menus
     */
    public static function addMenus($menus)
    {
        if (static::$menus === false) {
            static::$menus = array();
        }
        static::$menus = array_merge_recursive(static::$menus, $menus);
    }
    /**
     * Builds a menu
     *
     * @param       $menus
     * @param array $selected
     * @param array $attributes
     *
     * @return bool|string
     */
    protected static function buildMenu($menus, $selected = array(), array $attributes = array())
    {
        try {
            ob_start();
            echo '<ul '.HTML::attributes($attributes).'>';
            foreach ($menus as $menu) {

                if (array_key_exists('access', $menu) && !has_access($menu['access'])) {
                    continue;
                }
                $root = new TreeNode($menu);
                $it = new TreeNodesIterator(array($root));
                $rit = new RecursiveListIterator($it);
                $decor = new ListDecorator($rit, $selected);
                $rit->addDecorator($decor);

                foreach ($rit as $item) {
                    echo $item->getName();
                }
            }
            echo '</ul>';

            return ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            Error::exception($e);
        }
        return false;
    }

    /**
     * @param $data
     * @return string
     */
    private static function generateName(Menu $data)
    {
        switch ($data->type) {
            case 'link':
                return HTML::link($data->url, $data->text, $data->html_attributes);
                break;
            case 'action':
                return HTML::link(action($data->url, $data->url_params), $data->text, $data->html_attributes);
                break;
            default:
                return $data->text;
                break;
        }
    }
}
