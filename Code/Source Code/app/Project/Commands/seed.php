<?php
/**
 * File: seed.php
 * Created: 27-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
$db = new \Story\DB(config('database', null));

// Connect to databse server
$db->connect();

// Create seed object
$seeder = new \Story\Seeder;

// Set database connection
$seeder->db = $db;

// create the schemas
$args = isset($argv) ? array_filter(array_slice($argv, 2)) : array();
$seeder->run($args);
