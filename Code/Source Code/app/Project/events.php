<?php
/**
 * File: events.php
 * Created: 08-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

/*
|--------------------------------------------------------------------------
| Ip based spam protection
|--------------------------------------------------------------------------
|
| Stops the app if the ip is blacklisted
|
*/

use Project\Support\StoryEngine\ViewTemplateProvider;
use StoryEngine\StoryEngine;

event(
    'system.startup',
    null,
    function () {
        global $app;
        if (!$app['spamprotector']->check('ip')) {
            headers_sent() || header('HTTP/1.0 403 Forbidden');
            echo '<h1>Permission denied.</h1>';
            die ('<p>You do not have access to the site.</p>');
        }
    }
);
/*
|--------------------------------------------------------------------------
| Initialize the storyengine
|--------------------------------------------------------------------------
|
| Initializes the story engine with custom parser
|
*/

event(
    'system.startup',
    null,
    function () {

        init_storyengine();
    }
);

/*
|--------------------------------------------------------------------------
| Sets the last_login time
|--------------------------------------------------------------------------
|
| When the user logs in the last login time is set
|
*/

event(
    'user.login',
    null,
    function () {

        if (\Story\Auth::check()) {
            $user = \Story\Auth::user();
            $user->last_login = time();
            $user->save();
        }
    }
);
