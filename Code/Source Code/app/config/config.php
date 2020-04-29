<?php
/**
 * File: config.php
 * Created: 24-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
return array(
    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Set to true to enable debug mode.
    |
    */
    "debug"      => (bool) getenv('APP_DEBUG'),
    /*
    |--------------------------------------------------------------------------
    | Default timezone
    |--------------------------------------------------------------------------
    |
    | This is the system's default timezone.
    |
    */
    "timezone"   => getenv('APP_TIMEZONE'),
    /*
    |--------------------------------------------------------------------------
    | Available translations
    |--------------------------------------------------------------------------
    |
    | Set the languages that are available for the application.
    |  Will default to 'en'
    |
    */
    "languages"  => array("en_US"),
    /*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the command line tool.
	|
	*/
    'url' => getenv('URL'),

    /*
    |--------------------------------------------------------------------------
    | Database configuration
    |--------------------------------------------------------------------------
    |
    | This system uses PDO to connect to SQLite. Usually the default config
    | should work.
    | Visit http://us3.php.net/manual/en/pdo.drivers.php for more info.
    |
    | "dns"      => "sqlite:" . __DIR__ . "/../storage/database/database.sqlite"
    |
    */
    "database"   => array(
        "dns"      => "mysql:host=" . getenv('DB_HOST') . ";port=3306;dbname=" . getenv('DB_DATABASE'),
        "username" => getenv('DB_USERNAME'),
        "password" => getenv('DB_PASSWORD'),
        "params"   => array()
    ),
    /*
    |--------------------------------------------------------------------------
    | Cookies
    |--------------------------------------------------------------------------
    |
    | To insure your cookies are secure, please choose a long, random key!
    |
    */
    "cookie"     => array(
        "key"      => getenv('APP_KEY'),
        "timeout"  => time() + (60 * 60 * 4), // Ignore submitted cookies older than 4 hours
        "expires"  => 0, // Expire on browser close
        "path"     => "/",
        "domain"   => "",
        "secure"   => "",
        "httponly" => "",
    ),
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Authentication options
    |
    */
    "auth"       => array(
        // Inactivity timeout
        "timeout" => getenv('AUTH_TIMEOUT'), // Will sign out after 30 minutes of inactivity
    ),
    /*
    |--------------------------------------------------------------------------
    | Application key
    |--------------------------------------------------------------------------
    |
    | Choose a long random key!
    |
    */
    "key"        => getenv('APP_KEY'),
    /*
    |--------------------------------------------------------------------------
    | URL suffix
    |--------------------------------------------------------------------------
    |
    | This will be appended to all the generated URLs. For example if set to
    | '.html', a request will be /example.html
    |
    | To avoid conflict make sure you don't have html files in the public
    | folder with the same name.
    |
    */
    "url_suffix" => "",
    /*
    |--------------------------------------------------------------------------
    | Session
    |--------------------------------------------------------------------------
    |
    | Session storage handler. Currently not in use, but setting it to false
    | will disable session support for the application.
    |
    */
    'session'    => 'database',

    /*
    |--------------------------------------------------------------------------
    | Project/site title
    |--------------------------------------------------------------------------
    |
    | The site's title
    |
    */
    'title' => getenv('APP_TITLE'),

    /*
    |--------------------------------------------------------------------------
    | Admin path
    |--------------------------------------------------------------------------
    |
    | The admin path that leads to the administration interface
    |
    */

    "admin_path"    => getenv('APP_ADMIN_PATH'),
    /*
    |--------------------------------------------------------------------------
    | Items per page
    |--------------------------------------------------------------------------
    |
    | When listing how many items should we list
    |
    */

    "per_page"      => (int) getenv('APP_PER_PAGE'),

    /*
    |--------------------------------------------------------------------------
    | Maximum allowed upload file size
    |--------------------------------------------------------------------------
    |
    | Set the maximum allowed file size upload
    |
    */

    'max_upload_size' => (int) getenv('APP_MAX_UPLOAD_SIZE'),

    /*
    |--------------------------------------------------------------------------
    | Fallback role id's
    |--------------------------------------------------------------------------
    |
    | Set the default role id's. Change only when needed. These id's are locked
    | roles, so it's save to include the id's here, since the migrations will
    | restore these roles. Also when deleting a role, all users will be reverted
    | to this role.
    | Default: 3
    | User
    |
    */

    'default_roles' => array(3)
);
