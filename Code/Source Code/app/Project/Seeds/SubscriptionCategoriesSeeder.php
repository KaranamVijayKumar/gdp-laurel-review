<?php
/**
 * File: SubscriptionCategoriesSeeder.php
 * Created: 03-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Models\SubscriptionCategory;

class SubscriptionCategoriesSeeder
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
        $tbl = SubscriptionCategory::getTable();
        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $tbl);


        // 1yr
        $this->db->insert(
            $tbl,
            array(
                'id'      => 1,
                'name'    => '1 Year',
                'interval' => '12',
                'amount' => 10,
                'status' => 1,
                'description' => 'One year subscription.',
                'created' => time()
            )
        );

        // 2yr
        $this->db->insert(
            $tbl,
            array(
                'id'      => 2,
                'name'    => '2 Years',
                'interval' => '24',
                'amount' => 18,
                'status' => 1,
                'description' => 'Two year subscription.',
                'created' => time()
            )
        );
    }
}
