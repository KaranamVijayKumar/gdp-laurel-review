<?php
/*!
 * newsletter_send.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


/*
 * This command sends the newsletters. It sends 250 subscribers for each newsletter
 * Usage: php story newsletter:send
 * With cron this needs to be set to run every 30min. Example below:
*/

// every hour:
// 0 * * * * php story newsletter:send
// every 30min:
// */30 * * * * php story newsletter:send

ini_set('memory_limit', '384M');
ini_set('max_execution_time', 300);
set_time_limit(300);

load_database();
init_storyengine();

$postman = new \Project\Services\Newsletter\Postman();
$postman->setBatchSize(250);

$postman->sendAll();
