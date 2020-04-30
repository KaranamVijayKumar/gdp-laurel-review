<?php
/**
 * File: ConfigSeeder.php
 * Created: 24-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Story\Cipher;

class ConfigSeeder
{

    /**
     * @var \Story\DB
     */
    public $db;


    public function run()
    {

        $table = 'config';
        // Delete all from the users table
        $this->db->query("DELETE FROM {$this->db->i($table)}");




        // --------------------------------------------------------------
        // SMTP
        // --------------------------------------------------------------
        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp',
                'value' => '1',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp_host',
                'value' => '',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp_auth',
                'value' => '0',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp_username',
                'value' => '',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp_password',
                'value' => base64_encode(Cipher::encrypt('')),
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp_secure',
                'value' => '',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'smtp_port',
                'value' => '25',
                'type'  => 'string',
            )
        );
        // --------------------------------------------------------------
        // Mail from
        // --------------------------------------------------------------
        $this->db->insert(
            $table,
            array(
                'name'  => 'mail_from',
                'value' => '',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'mail_from_name',
                'value' => '',
                'type'  => 'string',
            )
        );
        // --------------------------------------------------------------
        // Contact form recipients
        // --------------------------------------------------------------
        $this->db->insert(
            $table,
            array(
                'name'  => 'contact_recipients',
                'value' => '',
                'type'  => 'string',
            )
        );
        // --------------------------------------------------------------
        // Issues
        // --------------------------------------------------------------
        $this->db->insert(
            $table,
            array(
                'name'  => 'latest_issue_price',
                'value' => '7.00',
                'type'  => 'string',
            )
        );
        $this->db->insert(
            $table,
            array(
                'name'  => 'back_issue_price',
                'value' => '5.00',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'issue_tax',
                'value' => '0',
                'type'  => 'string',
            )
        );

        // --------------------------------------------------------------
        // Chapbooks
        // --------------------------------------------------------------
        $this->db->insert(
            $table,
            array(
                'name'  => 'latest_chapbook_price',
                'value' => '7.00',
                'type'  => 'string',
            )
        );
        $this->db->insert(
            $table,
            array(
                'name'  => 'back_chapbook_price',
                'value' => '7.00',
                'type'  => 'string',
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'chapbook_tax',
                'value' => '0',
                'type'  => 'string',
            )
        );
        // --------------------------------------------------------------
        // Subscriptions
        // --------------------------------------------------------------
        $this->db->insert(
            $table,
            array(
                'name'  => 'subscription_allow_renew_before',
                'value' => 30,
                'type'  => 'string'
            )
        );

        $this->db->insert(
            $table,
            array(
                'name'  => 'subscription_renew_notify_days',
                'value' => json_encode(array(1, 25)),
                'type'  => 'json'
            )
        );

    }
}
