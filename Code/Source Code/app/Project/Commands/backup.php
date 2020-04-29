<?php
/**
 * File: backup.php
 * Created: 28-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
// Start database connection
$db = new \Story\DB(config('database', null));

// Connect to databse server
$db->connect();

// Create migration object
$migrationName = '\Story\Migration\\' . studly($db->pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
$migration = new $migrationName;


// Set database connection
$migration->db = $db;

// Load table configuration
$migration->tables = require(SP . 'config/migrations.php');

$migration->backupData();
