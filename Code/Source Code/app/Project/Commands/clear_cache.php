<?php
/**
 * File: clear_cache.php
 * Created: 30-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

ini_set('memory_limit', '384M');
ini_set('max_execution_time', 300);
set_time_limit(300);

$path = realpath(SP . 'storage/cache');

// --------------------------------------------------------------
// Cache dir
// --------------------------------------------------------------

/** @var \Project\Support\Cache\File $cacher */
$cacher = app('container')->make('Project\Support\Cache\CacheProviderInterface');

$path = realpath(SP . 'storage/cache');
if ($cacher instanceof \Project\Support\Cache\File) {

    $path = $cacher->getCachePath();

}

empty_dir($path, false);
@touch($path . DIRECTORY_SEPARATOR .'/.gitkeep');

print(colorize("\n" . _('Cache directory emptied.') . "\n", 'green'));
