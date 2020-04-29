<?php
/*!
 * toc_content.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<div class="media media--responsive">
    <div class="media__img palm--hidden u-1-of-5 u-1-of-1-palm text--center">
        <?php include __DIR__ .'/issue_aside.partial.php'; ?>
    </div>

    <div class="media__body">

        <?php
        /*
        <!-- .hero -->
        <div class="hero hero--secondary palm--hidden">
            <!-- .hero-issue -->
            <div class="flag flag--responsive hero-issue u-1-of-1 container">
                <div class="flag__body hero-issue__body u-p0">
                    <h2 class="">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Issues\Index', array($issue->slug)),
                            h($issue->title)
                        ) ?>
                    </h2>
                    <div class="hero-issue__description  text--secondary">
                        <?php echo $engine->getSection('issue-short_description'); ?>
                    </div>
                </div>
            </div><!-- /.hero-issue -->
        </div><!-- /.hero -->
        */
        ?>
        <?php include __DIR__ .'/toc_content.partial.php' ?>

        <hr/>
        <p>
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Issues\Index', array($issue->slug)),
                sprintf(_('Back to <q>%s</q>'), $issue->title)
            ) ?>
        </p>
    </div>
</div>
<div class="layout__item u-ph0">
    <?php
    if (count($issues)) { ?>

        <h2 class="text--center content-hero content-hero--secondary">
            <?php echo _('Other issues') ?>
        </h2>
        <div class="text--center">
            <?php include __DIR__ .'/items.partial.php' ?>
        </div>

    <?php
    } ?>
</div>

<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
// global content aside

$global_content_aside = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
// extra head

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
 // extra footer

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// $breadcrumbs
// --------------------------------------------------------------
$breadcrumbs = array(
    \Story\HTML::link('', _('Home')),
    \Story\HTML::link(action('\Project\Controllers\Issues\Index'), _('Issues')),
    \Story\HTML::link(action('\Project\Controllers\Issues\Index', array($issue->slug)), $issue->title),
    \Story\HTML::link(action('\Project\Controllers\Issues\TocContent', array($issue->slug, $toc_content->slug)), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
