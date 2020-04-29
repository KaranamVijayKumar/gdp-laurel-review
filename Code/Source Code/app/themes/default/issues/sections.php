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

use Project\Models\Issue;

/*
|--------------------------------------------------------------------------
| Issue slider
|--------------------------------------------------------------------------
|
| Shows the latest issues along with the issue contents
|
*/

$engine->section(
    'issue-slider',
    function ($section) {
        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // set the template
        $section->setTemplate('issues/issue_slider.partial');

        // we cache the latest news for 1 minute
        $section->setCache(array('file', 60));
       $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));
       $section->setCacheName('issue-slider');


        // if the section is cached, we do not need to get the news from the db
        if (!$section->getCache()->get('issue-slider')) {

            $db = load_database();
            $where = array(
                'sql' => $db->i(Issue::getTable() . '.status') .'= 1',
                'values' => array()
            );
            $items = Issue::withTocHighlights(1, 1, $where);


            // we calculate the optimal slider time
            // assuming the toc content will be up 7seconds (can be set in the theme.json)
            $content_change = app('theme')->getOption('slider_change', 7000);

            $change = 1;
            foreach ($items as $item) {
                if (isset($item->highlights)) {
                    $change = max(count($item->highlights), $change);
                }
            }
            $change = $content_change * $change;

            // set the data
            $section->setData(compact('items', 'change', 'content_change'));
        }
    }
);

/*
|--------------------------------------------------------------------------
| Latest last 3 issues in the sidebar
|--------------------------------------------------------------------------
|
| Displays last 3 issues in the sidebar
|
*/

$engine->section(
    'aside-last-issues',
    function ($section) {


        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // we cache the hero for 10 minutes with the file cacher
        $section->setCache(array('file', 60)); // cache for 1 minutes
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));
        $section->setCacheName('aside-last-issues');


        // set the template
        $section->setTemplate('issues/aside_last_issues.partial');

        if (!$section->getCache()->get('aside-last-issues')) {
            $where = array(
                'sql'    => Issue::$db->i(Issue::getTable()) . '.' . Issue::$db->i('status') . ' = ?',
                'values' => array('1')
            );

            $issues = Issue::listIssues(1, 2, $where);
            $section->setData(array('issues' => $issues['items']));
        }

    }
);

/*
|--------------------------------------------------------------------------
| Last issue
|--------------------------------------------------------------------------
|
| Last issue
|
*/

$engine->section(
    'aside-last-issue',
    function ($section) {


        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // we cache the hero for 10 minutes with the file cacher
        $section->setCache(array('file', 60)); // cache for 1 minutes
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));
        $section->setCacheName('aside-last-issue');


        // set the template
        $section->setTemplate('issues/aside_last_issue.partial');

        if (!$section->getCache()->get('aside-last-issue')) {
            $issue = Issue::getLast();
            $section->setData(array('issue' => $issue));
        }

    }
);
