<?php
/**
 * File: down.php
 * Created: 29-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

// when we execute this command, we write a file named "down" to storage
// which will be checked when the app boots

$file = SP . 'storage/down';

touch ($file);

print colorize("\nApplication is down.\n\n", 'red');
