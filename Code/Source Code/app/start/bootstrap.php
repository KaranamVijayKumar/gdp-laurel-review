<?php
/**
 * File: bootstrap.php
 * Created: 24-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

// System Start Time
define('START_TIME', microtime(true));

// System Start Memory
define('START_MEMORY_USAGE', memory_get_usage());

define('VERSION', '1.0RC1');

// --------------------------------------------------------------
// System check
// --------------------------------------------------------------

foreach (array('intl', 'mcrypt', 'iconv', 'gettext', 'curl') as $extension) {
    if (!extension_loaded($extension)) {
        echo ucwords($extension) . ' PHP extension required.' . PHP_EOL;
        exit(1);
    }
}

// --------------------------------------------------------------
// Constants
// --------------------------------------------------------------

// Extension of all PHP files
define('EXT', '.php');

// Directory separator (Unix-Style works on all OS)
define('DS', '/');

// Absolute path to the system folder
define('SP', realpath(__DIR__ . '/../') . DS);

// Absolute path to locale
define('SL', realpath(SP . 'locale'));

// Is this an AJAX request?
define('AJAX_REQUEST', strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');


// The current site path
define('PATH', parse_url(getenv('REQUEST_URI'), PHP_URL_PATH));

// Define the files folder
define('UPLOADS_PATH', realpath(SP . 'storage/files') . '/');

// --------------------------------------------------------------
// Check if app is down
// --------------------------------------------------------------
if (file_exists(SP . 'storage/down') && php_sapi_name() !== 'cli') {
    headers_sent() || header('HTTP/1.0 503 Service Temporarily Unavailable');
    // change the content if needed
    echo "<h1>We'll be right back</h1>";
    echo "<p>The site is down for maintenance.</p>";
    exit;
}


$app = array();


// --------------------------------------------------------------
// Autoload
// --------------------------------------------------------------

require_once(SP . 'vendor/autoload.php');

// set up the env vars
Dotenv::load(dirname(__DIR__));
Dotenv::required(
    array(
        'APP_ADMIN_PATH',
        'APP_DEBUG',
        'APP_KEY',
        'APP_MAX_UPLOAD_SIZE',
        'APP_PER_PAGE',
        'APP_TIMEZONE',
        'APP_TITLE',

        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',

        'AUTH_TIMEOUT',

        'BOX_API_KEY',
        'PAYPAL_SANDBOX',
        'PAYPAL_MERCHANT_ID',
        'PAYPAL_MERCHANT_EMAIL'
    )
);

// --------------------------------------------------------------
// Base config
// --------------------------------------------------------------

$app['config'] = require_once(SP . 'config/config.php');

// --------------------------------------------------------------
// Domain name
// --------------------------------------------------------------

// The current TLD address, scheme, and port
if (PHP_SAPI !== 'cli') {
    define(
        'DOMAIN',
        (strtolower(getenv('HTTPS')) == 'on' ? 'https' : 'http') . '://' .
        getenv('SERVER_NAME') . (($p = getenv('SERVER_PORT')) != 80 && $p != 443 ? ":$p" : '')
    );
} else {
    define('DOMAIN', $app['config']['url']);
}


// --------------------------------------------------------------
// Setup
// --------------------------------------------------------------

// Initialize the main db
$app['db'] = new \Story\DB($app['config']['database']);

// --------------------------------------------------------------
// Themes
// --------------------------------------------------------------
$app['theme'] = new \Project\Support\Theme(config('theme', 'default'));

// Set the view
\Story\View::$directory = $app['theme']->getThemesPath();

// Set up the cookies
\Story\Cookie::$settings = $app['config']['cookie'];

// Init the session
if (isset($app['config']['session']) && $app['config']['session']) {
    $app['session'] = new \Story\Session($app['db']);
}


// --------------------------------------------------------------
// Locale
// --------------------------------------------------------------

// Get locale from user agent
if (isset($_COOKIE['lang'])) {
    $preference = $_COOKIE['lang'];
} else {
    $preference = Locale::acceptFromHttp(getenv('HTTP_ACCEPT_LANGUAGE'));
}

// Match preferred language to those available, defaulting to generic English
$app['locale'] = Locale::lookup($app['config']['languages'], $preference, false, 'en_US');

// Default locale
Locale::setDefault($app['locale']);
putenv("LANG=" . $app['locale']);
setlocale(LC_ALL, $app['locale'] . '.utf-8');
btd('common', true);
// Set carbon locale
$file = SP . 'vendor/nesbot/carbon/src/Carbon/Lang/' . $app['locale'] .'.php';
// fallback to generic en if carbon has no translation
\Carbon\Carbon::setLocale(file_exists($file) ? $app['locale'] : 'en');

// --------------------------------------------------------------
// Spam protector
// --------------------------------------------------------------
$app['spamprotector'] = new \StorySpamProtector\SpamProtector('database');

// --------------------------------------------------------------
// Timezone
// --------------------------------------------------------------
// Default timezone of server
date_default_timezone_set($app['config']['timezone']);

// --------------------------------------------------------------
// Encoding
// --------------------------------------------------------------
// iconv encoding
if (version_compare(PHP_VERSION, '5.6', '>=')) {
    @ini_set('default_charset', 'UTF-8');
} else {
    iconv_set_encoding("internal_encoding", "UTF-8");
    iconv_set_encoding("input_encoding", "UTF-8");
    iconv_set_encoding("output_encoding", "UTF-8");
}

// multibyte encoding
mb_internal_encoding('UTF-8');

// Enable global error handling
set_error_handler(array('\Story\Error', 'handler'));
register_shutdown_function(array('\Story\Error', 'fatal'));
