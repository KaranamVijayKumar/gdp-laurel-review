<?php
/**
 * File: PagesSeeder.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Models\Page;

class PagesSeeder
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
        $tbl = Page::getTable();
        $now = time();
        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i($tbl));


        // --------------------------------------------------------------
        // about
        // --------------------------------------------------------------
        $this->db->insert(
            $tbl,
            array(
                'id' => 1,
                'slug'    => 'about',
                'view'   => 'page',
                'status' => 1,
                'locked' => 1,
                'created' => $now,
            )
        );
        // --------------------------------------------------------------
        // contact
        // --------------------------------------------------------------
        $this->db->insert(
            $tbl,
            array(
                'id' => 2,
                'slug'    => 'contact',
                'view'   => 'contact',
                'status' => 1,
                'locked' => 1,
                'created' => $now,
            )
        );
        // --------------------------------------------------------------
        // submissions
        // --------------------------------------------------------------
        $this->db->insert(
            $tbl,
            array(
                'id' => 3,
                'slug'    => 'submissions',
                'view'   => 'page',
                'status' => 1,
                'locked' => 1,
                'created' => $now,
            )
        );

        // --------------------------------------------------------------
        // subscriptions
        // --------------------------------------------------------------
        $this->db->insert(
            $tbl,
            array(
                'id' => 4,
                'slug'    => 'subscriptions',
                'view'   => 'page',
                'status' => 1,
                'locked' => 1,
                'created' => $now,
            )
        );

    }
}
