<?php
/*!
 * show.php v0.1
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
    <div class="media__img  u-1-of-5 u-1-of-1-palm text--center">
        <?php include __DIR__ .'/chapbook_aside.partial.php'; ?>
    </div>

    <div class="media__body">
        <!-- .hero -->
        <div class="hero hero--secondary">
            <!-- .hero-issue -->
            <div class="flag hero-issue u-p0">
                <div class="flag__body hero-issue__body u-p0">
                    <h2 class="">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)),
                            h($chapbook->title)
                        ) ?>
                    </h2>
                    <div class="text--user pt--">
                        <?php echo $engine->getSection('chapbook-short_description'); ?>
                    </div>
                </div>
            </div><!-- /.hero-issue -->
        </div><!-- /.hero -->

        <?php echo $engine->getSection('chapbook-before TOC'); ?>

        <?php include __DIR__ .'/toc.partial.php' ?>

        <?php echo $engine->getSection('chapbook-after TOC'); ?>
        <hr/>
        <p>
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Chapbooks\Index'),
                sprintf(_('Back to chapbooks'))
            ) ?>
        </p>
    </div>
</div>
<div class="layout__item ph0">
    <?php
    if (count($chapbooks)) { ?>

        <h2 class="text--center content-hero content-hero--secondary">
            <?php echo _('Other chapbooks') ?>
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
    \Story\HTML::link(action('\Project\Controllers\Chapbooks\Index'), _('Chapbooks')),
    \Story\HTML::link(action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
