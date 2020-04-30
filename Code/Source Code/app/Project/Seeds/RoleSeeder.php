<?php
/**
 * File: RoleSeeder.php
 * Created: 05-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Support\PermissionRepository;
use Project\Support\Roles;

/**
 * Class RoleSeeder
 *
 * @package Project\Seeds
 */
class RoleSeeder
{

    /**
     * @var \Story\DB
     */
    public $db;

    /**
     *
     */
    public function __construct()
    {
        $this->repo = new PermissionRepository(SP . 'config/permissions.json');

    }

    /**
     *
     */
    public function run()
    {


        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i . 'roles' . $this->db->i);

        // Owner
        $this->db->insert(
            'roles',
            array(
                'id'          => 1,
                'name'        => 'Owner',
                'locked'      => 1,
                'order'       => 0,
                'permissions' => json_encode(array(Roles::ALL_PERMISSIONS))
            )
        );

        // admin
        $this->db->insert(
            'roles',
            array(
                'id'          => 2,
                'name'        => 'Administrator',
                'locked'      => 1,
                'order'       => 1,
                'permissions' => json_encode(array(Roles::ALL_PERMISSIONS))
            )
        );

        // user
        $this->db->insert(
            'roles',
            array(
                'id'          => 3,
                'name'        => 'User',
                'locked'      => 1,
                'order'       => 2,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'account.dashboard',
                        'account.biography',
                        'account.contact',
                        'account.delete',
                        'account.email',
                        'account.password',
                        'submissions',
                        'subscriptions'
                    )
                )
            )
        );

        // visitor
        $this->db->insert(
            'roles',
            array(
                'id'          => 4,
                'name'        => 'Guest',
                'locked'      => 1,
                'order'       => 3,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'pages',
                        'cart',
                        'account.create',
                        'issues',
                        'chapbooks',
                        'news',
                        'newsletter',
                        'contact'
                    )
                ),
                'default'     => 1
            )
        );

        // submission staff
        $this->db->insert(
            'roles',
            array(
                'id'          => 5,
                'name'        => 'Submission Staff',
                'locked'      => 0,
                'order'       => 4,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'admin.dashboard',
                        'admin.editor_assets',
                        'admin.submissions.accept',
                        'admin.submissions.comment',
                        'admin.submissions.create',
                        'admin.submissions.download',
                        'admin.submissions.edit',
                        'admin.submissions.like',
                        'admin.submissions.send_email',
                        'admin.submissions.view',
                        'admin.submissions.view_author'
                    )
                )
            )
        );

        // subscription managers
        $this->db->insert(
            'roles',
            array(
                'id'          => 6,
                'name'        => 'Subscription Manager',
                'locked'      => 0,
                'order'       => 5,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'admin.dashboard',
                        'admin.subscriptions.view',
                        'admin.subscriptions.create',
                        'admin.subscriptions.edit',
                        'admin.subscriptions.delete'
                    )
                )
            )
        );

        // Issue manager
        $this->db->insert(
            'roles',
            array(
                'id'          => 7,
                'name'        => 'Issue and Chapbook Manager',
                'locked'      => 0,
                'order'       => 6,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'admin.dashboard',
                        'admin.editor_assets',
                        'admin.issues.view',
                        'admin.issues.create',
                        'admin.issues.edit',
                        'admin.issues.delete',
                        'admin.chapbooks.view',
                        'admin.chapbooks.create',
                        'admin.chapbooks.edit',
                        'admin.chapbooks.delete'
                    )
                )
            )
        );

        // News/Newsletter editor
        $this->db->insert(
            'roles',
            array(
                'id'          => 8,
                'name'        => 'News / Newsletter Editor',
                'locked'      => 0,
                'order'       => 7,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'admin.dashboard',
                        'admin.editor_assets',
                        'admin.news',
                        'admin.newsletter'
                    )
                )
            )
        );


        // order and payment manager
        $this->db->insert(
            'roles',
            array(
                'id'          => 9,
                'name'        => 'Orders and Payments manager',
                'locked'      => 0,
                'order'       => 7,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'admin.dashboard',
                        'admin.editor_assets',
                        'admin.orders'
                    )
                )
            )
        );

        // content manager
        $this->db->insert(
            'roles',
            array(
                'id'          => 50,
                'name'        => 'Content Manager',
                'locked'      => 0,
                'order'       => 50,
                'permissions' => json_encode(
                    $this->repo->toArray(
                        'admin.dashboard',
                        'admin.editor_assets',
                        'admin.pages',
                        'admin.snippets',
                        'admin.files'
                    )
                )
            )
        );

    }
}
