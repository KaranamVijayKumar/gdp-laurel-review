<?php
/*!
 * notification_subscription_expire.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

/*
 * This command send email notification to the users who have subscriptions that are about to expire.
 * Usage: php story notification:subscription:expire
 * With cron this needs to be set to run every day at. Examples below:
*/

// This example runs every 30 min at 17:00-23:30 (14 times/day) allowing to send 750 emails/day
// 0,30 17,18,19,20,21,22,23 * * * php story notification:subscription:expire


ini_set('memory_limit', '384M');
ini_set('max_execution_time', 300);
set_time_limit(300);

$notifier = new \Project\Support\Subscriptions\ExpireNotifications('database');

$notifier->send();
