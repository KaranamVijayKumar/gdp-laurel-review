<?php
/**
 * File: up.php
 * Created: 29-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
// when we execute this command, we delete a file named "down" from storage
// which will be checked when the app boots

$file = SP . 'storage/down';

@unlink($file);

print colorize("\nApplication is up.\n\n", 'green');
