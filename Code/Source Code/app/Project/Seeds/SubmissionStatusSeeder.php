<?php
/**
 * File: SubmissionStatusSeeder.php
 * Created: 26-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Models\SubmissionStatus;

class SubmissionStatusSeeder
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
        $this->db->query('DELETE FROM ' . $this->db->i('submission_statuses'));


        // New
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 1,
                'name'        => 'New',
                'slug'        => SubmissionStatus::STATUS_NEW,
                'order'       => 0,
                'created'  => time()
            )
        );

        // in progress
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 2,
                'name'        => 'In-Progress',
                'slug'        => SubmissionStatus::STATUS_PROGRESS,
                'order'       => 1,
                'created'  => time()
            )
        );

        // accepted
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 3,
                'name'        => 'Accepted',
                'slug'        => SubmissionStatus::STATUS_ACCEPTED,
                'order'       => 2,
                'created'  => time()
            )
        );

        // signed
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 4,
                'name'        => 'Signed',
                'slug'        => SubmissionStatus::STATUS_SIGNED,
                'order'       => 3,
                'created'  => time()
            )
        );

        // declined
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 5,
                'name'        => 'Declined',
                'slug'        => SubmissionStatus::STATUS_DECLINED,
                'order'       => 5,
                'created'  => time()
            )
        );

        // withdrawn
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 6,
                'name'        => 'Partially withdrawn',
                'slug'        => SubmissionStatus::STATUS_PARTIAL_WITHDRAWN,
                'order'       => 6,
                'created'  => time()
            )
        );

        // withdrawn
        $this->db->insert(
            'submission_statuses',
            array(
                'id'          => 7,
                'name'        => 'Withdrawn',
                'slug'        => SubmissionStatus::STATUS_WITHDRAWN,
                'order'       => 7,
                'created'  => time()
            )
        );
    }
}
