<?php
/**
 * File: update_blacklists.php
 * Created: 24-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

ini_set('memory_limit', '384M');
ini_set('max_execution_time', 300);
set_time_limit(300);

$sp = new \StorySpamProtector\SpamProtector('database');

$sp->update();
