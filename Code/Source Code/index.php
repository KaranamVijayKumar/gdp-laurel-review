<?php
/*!
 * index.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2014 Arpad Olasz
 * All rights reserved.
 *
 */



/*
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=laurelre", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

exit;*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[]                       = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

// define the public path
define('PP', __DIR__ . '/');

// full request path
define('PR', preg_replace('#/+#', '/', dirname($_SERVER['SCRIPT_NAME']) . '/'));

require_once 'app/start/bootstrap.php';

try {

    // Anything else before we start?
    event('system.startup');

    // Load controller dispatch passing URL routes
    $dispatch = new \Story\Dispatch(require_once (SP . 'config/routes.php'));

    // Run controller based on URL path and HTTP request method
    $controller = $dispatch->controller(substr(PATH, strlen(PR)));

    // Send the controller response
    $controller->send();

    // One last chance to do something
    event('system.shutdown', $controller);

} catch (Exception $e) {
    \Story\Error::exception($e);
}
