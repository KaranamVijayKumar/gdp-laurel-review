<?php
/**
 * File: MenusSeeder.php
 * Created: 27-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Models\Menu;

class MenusSeeder
{
    /**
     * @var \Story\DB
     */
    public $db;

    protected $table;

    protected $created;

    protected $increment = 1;

    public function __construct()
    {
        $this->table = Menu::getTable();
        $this->created = time();
    }


    public function run()
    {
        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i($this->table));

        // nav-site
        $this->seedNavSite();

        // nav-footer
        $this->seedNavFooter();

    }

    private function seedNavSite()
    {
        $menu_name = 'nav-site';
        // submissions
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => $menu_name,
                'item_id' => 'submissions',
                'type' => 'link',
                'access' => null,
                'text' => _('Submissions'),
                'url' => '/submissions',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '1',
                'created' => $this->created
            )
        );
        $this->increment++;

        // subscriptions
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => $menu_name,
                'item_id' => 'subscriptions',
                'type' => 'link',
                'access' => null,
                'text' => _('Subscriptions'),
                'url' => '/subscriptions',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '2',
                'created' => $this->created
            )
        );
        $this->increment++;

        // issues
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => $menu_name,
                'item_id' => 'issues',
                'type' => 'action',
                'access' => null,
                'text' => _('Issues'),
                'url' => '\Project\Controllers\Issues\Index',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '3',
                'created' => $this->created
            )
        );
        $this->increment++;

        // issues > chapbooks
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => $this->increment - 1,
                'menu_name' => $menu_name,
                'item_id' => 'chapbooks',
                'type' => 'action',
                'access' => null,
                'text' => _('Chapbooks'),
                'url' => '\Project\Controllers\Chapbooks\Index',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '1',
                'created' => $this->created
            )
        );
        $this->increment++;

        // news
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => $menu_name,
                'item_id' => 'news',
                'type' => 'action',
                'access' => null,
                'text' => _('News'),
                'url' => '\Project\Controllers\News\Index',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '4',
                'created' => $this->created
            )
        );
        $this->increment++;

        // news > newsletter
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => $this->increment - 1,
                'menu_name' => $menu_name,
                'item_id' => 'newsletter',
                'type' => 'action',
                'access' => null,
                'text' => _('Newsletter'),
                'url' => '\Project\Controllers\Newsletter\Index',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '1',
                'created' => $this->created
            )
        );
        $this->increment++;

        // news > contests
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => $this->increment - 2,
                'menu_name' => $menu_name,
                'item_id' => 'contests',
                'type' => 'link',
                'access' => null,
                'text' => _('Contests'),
                'url' => '/contests',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '2',
                'created' => $this->created
            )
        );
        $this->increment++;

        // about
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => $menu_name,
                'item_id' => 'about',
                'type' => 'link',
                'access' => null,
                'text' => _('About Us'),
                'url' => '/about',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '5',
                'created' => $this->created
            )
        );
        $this->increment++;

        // contact
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => $menu_name,
                'item_id' => 'contact',
                'type' => 'action',
                'access' => null,
                'text' => _('Contact Us'),
                'url' => '\Project\Controllers\ContactController',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '6',
                'created' => $this->created
            )
        );
        $this->increment++;
    }

    private function seedNavFooter()
    {
        // submissions
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => 'nav-footer',
                'item_id' => 'submissions',
                'type' => 'link',
                'access' => null,
                'text' => _('Submissions'),
                'url' => '/submissions',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '1',
                'created' => $this->created
            )
        );
        $this->increment++;
        // subscriptions
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => 'nav-footer',
                'item_id' => 'subscriptions',
                'type' => 'link',
                'access' => null,
                'text' => _('Subscriptions'),
                'url' => '/subscriptions',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '2',
                'created' => $this->created
            )
        );
        $this->increment++;

        // issues
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => 'nav-footer',
                'item_id' => 'issues',
                'type' => 'action',
                'access' => null,
                'text' => _('Issues'),
                'url' => '\Project\Controllers\Issues\Index',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '3',
                'created' => $this->created
            )
        );
        $this->increment++;



        // news
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => 'nav-footer',
                'item_id' => 'news',
                'type' => 'action',
                'access' => null,
                'text' => _('News'),
                'url' => '\Project\Controllers\News\Index',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '4',
                'created' => $this->created
            )
        );
        $this->increment++;

        // about
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => 'nav-footer',
                'item_id' => 'about',
                'type' => 'link',
                'access' => null,
                'text' => _('About Us'),
                'url' => '/about',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '5',
                'created' => $this->created
            )
        );
        $this->increment++;

        // contact
        $this->db->insert(
            $this->table,
            array(
                'id'  => $this->increment,
                'parent_id' => null,
                'menu_name' => 'nav-footer',
                'item_id' => 'contact',
                'type' => 'action',
                'access' => null,
                'text' => _('Contact Us'),
                'url' => '\Project\Controllers\ContactController',
                'url_params' => null,
                'html_attributes' => null,
                'status' => '1',
                'order' => '6',
                'created' => $this->created
            )
        );
        $this->increment++;
    }
}
