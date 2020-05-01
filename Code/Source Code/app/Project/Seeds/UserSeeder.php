<?php
/**
 * File: UserSeeder.php
 * Created: 27-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Story\Cipher;
use Story\ORM;

class UserSeeder
{

    /**
     * @var \Story\DB
     */
    public $db;


    public function run()
    {

        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i . 'users' . $this->db->i);

        // Sample owner
        $this->db->insert(
            'users',
            array(
                'id'       => 1,
                'email'    => 'owner@example.com',
                'password' => base64_encode(Cipher::encrypt('owner123')),
                'active'   => 1,
                'created'  => time()
            )
        );

        // admin
        $this->db->insert(
            'users',
            array(
                'id'       => 2,
                'email'    => 'admin@example.com',
                'password' => base64_encode(Cipher::encrypt('admin123')),
                'active'   => 1,
                'created'  => time()
            )
        );

        // sample user
        $this->db->insert(
            'users',
            array(
                'id'       => 3,
                'email'    => 'user@example.com',
                'password' => base64_encode(Cipher::encrypt('user1234')),
                'active'   => 1,
                'created'  => time()
            )
        );

    }
}
