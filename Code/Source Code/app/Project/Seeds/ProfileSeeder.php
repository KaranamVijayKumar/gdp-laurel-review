<?php
/**
 * File: ProfileSeeder.php
 * Created: 31-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Models\Profile;

class ProfileSeeder
{

    /**
     * @var \Story\DB
     */
    public $db;


    public function run()
    {
        $tbl = $this->db->i(Profile::getTable());
        // Delete all from the users table
        $this->db->query("DELETE FROM {$tbl}");

        // owner profile
        $this->db->insert(
            'profiles',
            array(
                'user_id' => 1,
                'name'    => 'name',
                'value'   => 'Owner',
            )
        );

        // admin profile
        $this->db->insert(
            'profiles',
            array(
                'user_id' => 2,
                'name'    => 'name',
                'value'   => 'Administrator',
            )
        );

        // user profile
        $this->db->insert(
            'profiles',
            array(
                'user_id' => 3,
                'name'    => 'name',
                'value'   => 'Sample user',
            )
        );

    }
}
