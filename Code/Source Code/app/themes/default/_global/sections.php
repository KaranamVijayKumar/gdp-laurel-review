<?php
/*!
 * sections.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Project\Models\Issue;

/** @var \StoryEngine\StoryEngine $engine */

/*
|--------------------------------------------------------------------------
| Last issue blurred background style
|--------------------------------------------------------------------------
|
| Provides the latest issue blurred css style
|
*/


$engine->section(
    'header-background-image',
    function ($section) {

        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // we cache the hero for 10 minutes with the file cacher
        $section->setCache(array('file', 60)); // cache for 1 minutes. One caveeat for cache, is that
        // when the issue cover page is changed, the site won't have a blurred cover page image
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));

        // set the template
        $section->setTemplateProvider('plain');
        $section->setTemplate('');

        if (!$section->getCache()->get('header-background-image')) {
            $issue = Issue::getLast();
            if ($issue) {
                $path = \Project\Models\IssueFile::createBlurredCoverPageImageUrl($issue->storage_name);
                $section->setTemplate('background-image: url(\'' . $path .'\')');
            }
        }

    }
);
