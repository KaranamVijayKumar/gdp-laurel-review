<?php
/**
 * File: UserRolesSeeder.php
 * Created: 05-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

/**
 * Class UserRolesSeeder
 *
 * @package Project\Seeds
 */
class UserRolesSeeder
{

    /**
     * @var \Story\DB
     */
    public $db;


    /**
     *
     */
    public function run()
    {

        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i . 'user_roles' . $this->db->i);

        // Sample owner
        $this->db->insert(
            'user_roles',
            array(
                'id'      => 1,
                'user_id' => 1,
                'role_id' => 1
            )
        );

        // sample admin
        $this->db->insert(
            'user_roles',
            array(
                'id'      => 2,
                'user_id' => 2,
                'role_id' => 2
            )
        );

        // sample user
        $this->db->insert(
            'user_roles',
            array(
                'id'      => 3,
                'user_id' => 3,
                'role_id' => 3
            )
        );


    }
}
