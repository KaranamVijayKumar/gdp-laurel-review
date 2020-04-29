<?php
/*!
 * sections.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */
/** @var \StoryEngine\StoryEngine $engine */

/*
|--------------------------------------------------------------------------
| Latest news
|--------------------------------------------------------------------------
|
| Displays the latest news
|
*/

$engine->section(
    'latest-news',
    function ($section) {


        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // set the template
        $section->setTemplate('news/latest_news.partial');

        // we cache the latest news for 1 minute
        $section->setCache(array('file', 60));
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));

        // if the section is cached, we do not need to get the news from the db
        if (!$section->getCache()->get('latest-news')) {
            $news_factory = new \Project\Support\News\NewsFactory();
            // set the data
            $section->setData(array('news' => $news_factory->latest()));
        }
    }
);


/*
|--------------------------------------------------------------------------
| Older news for the news page
|--------------------------------------------------------------------------
|
| Older news that are not the latest ones
|
*/

$engine->section(
    'older-news',
    function ($section) {

        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // set the template
        $section->setTemplate('news/older_news.partial');

        $cache_name = 'older-news-' . (int) get('page', 1);
        // we cache the older news for 1 minute
        $section->setCache(array('file', 60));
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));
        $section->setCacheName($cache_name);

        // if the section is cached, we do not need to get the news from the db
        if (!$section->getCache()->get($cache_name)) {
            $news_factory = new \Project\Support\News\NewsFactory();
            // set the data
            $section->setData($news_factory->older());
        }
    }
);


/*
|--------------------------------------------------------------------------
| Other news
|--------------------------------------------------------------------------
|
| This will show other news next to a news article
|
*/

$engine->section(
    'other-news',
    function ($section) {
        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        global $controller;

        if (property_exists($controller, 'article')) {

            $skip_article = $controller->article;

            $news_factory = new \Project\Support\News\NewsFactory();
            // set the data
            $section->setData(array('news' => $news_factory->latest($skip_article)));

            // set the template
            $section->setTemplate('news/other_news.partial');
        }
    }
);
