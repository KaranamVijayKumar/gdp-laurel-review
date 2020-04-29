<?php
/**
 * File: restore.php
 * Created: 28-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

ini_set('memory_limit', '384M');
ini_set('max_execution_time', 300);
set_time_limit(300);

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

// story restore --backup=name
$migration->restoreData(isset($argv[2]) ? substr($argv[2], 9) : '_current_backup');
