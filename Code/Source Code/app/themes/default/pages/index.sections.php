<?php
/*!
 * welcome.sections.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */
/** @var \StoryEngine\StoryEngine $engine */

/*
|--------------------------------------------------------------------------
| Welcome page hero
|--------------------------------------------------------------------------
|
| Welcome page hero
|
*/
$engine->section(
    'welcome-hero',
    function ($section) {
        require_once __DIR__ .'/../issues/sections.php';
        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // We are using the welcome-hero-issues section as the content
        // with a plain template provider to render the slider.
        $section->setTemplateProvider('plain');
        $section->setTemplate(story_section('issue-slider'));
    }
);

/*
|--------------------------------------------------------------------------
| Welcome page global features
|--------------------------------------------------------------------------
|
| Welcome page global features (3 snippets)
|
*/
$engine->section(
    'welcome-global-features',
    function ($section) {

        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // set the template
        $section->setTemplate('pages/global_features.partial');

        // we cache the latest news for 1 minute
        $section->setCache(array('file', 60));
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));

        if (!$section->getCache()->get('welcome-global-features')) {

            $items = array(
                snippet('global-features-submissions'),
                snippet('global-features-subscriptions'),
                snippet('global-features-issues'),

            );
            $section->setData(array('items' => array_filter($items)));

        }

    }
);
