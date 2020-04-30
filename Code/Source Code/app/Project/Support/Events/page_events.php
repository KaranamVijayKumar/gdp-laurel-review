<?php
/**
 * File: page_events.php
 * Created: 27-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

/*
|--------------------------------------------------------------------------
| Page saved event
|--------------------------------------------------------------------------
|
*/

event(
    'page.saved',
    null,
    function ($page) {
        /** @var \Project\Models\Page $page */
        $cache_name = $page->getCacheName();
        if ($cache_name) {
            $page->page_cache->forget($cache_name);
        }


    }
);

/*
|--------------------------------------------------------------------------
| Page deleted event
|--------------------------------------------------------------------------
|
| Should be executed before the deleting from the db
|
*/

event(
    'page.deleted',
    null,
    function ($page) {
        /** @var \Project\Models\Page $page */
        $cache_name = $page->getCacheName();
        if ($cache_name) {
            $page->page_cache->forget($cache_name);
        }

    }
);
