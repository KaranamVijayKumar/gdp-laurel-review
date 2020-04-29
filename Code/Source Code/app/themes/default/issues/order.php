<?php
/*!
 * order.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\Form;
use Story\HTML;

ob_start();
?>
<div class="media media--responsive">
    <div class="media__img u-1-of-5 u-1-of-1-palm text--center palm--hidden">
        <?php include __DIR__ .'/issue_aside.partial_wo_order.php'; ?>
    </div>
    <div class="media__body">
        <!-- .hero -->
        <div class="hero hero--secondary palm--hidden">
            <!-- .hero-issue -->
            <div class="flag hero-issue">
                <div class="flag__body hero-issue__body">
                    <h2 class="mt-">
                        <?php
                        echo HTML::link(
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

        <!-- price -->
        <div class="flag flag--rev">
            <div class="flag__img gamma"><?php echo money_format('%n', $price) ?></div>
            <div class="flag__body">
                <h3 class="u-mb"><?php echo sprintf(_('Issue <q>%s</q>'), $issue->title) ?></h3>
            </div>
        </div>
        <hr class="mt0"/>
        <!-- /price -->

        <?php require __DIR__ . '/../_global/notifications.php'; ?>

        <?php
        if (app('cart') && $in_stock) { ?>

            <?php echo \Story\Form::open(array('errors'=>$errors)) ?>

            <div class="cf"></div>
            <p class="text--right">
                <?php
                echo \Story\Form::button(
                    _('Add to Cart'),
                    array('type' => 'submit', 'class' => 'btn btn--alert  u-1-of-1-palm')
                ) ?>
            </p>

            <?php echo \Story\Form::close() ?>

        <?php
        } else { ?>

            <p class="text--right">
                <?php echo _('Out of Stock.') ?>
            </p>

        <?php
        } ?>

        <hr/>
        <p>
            <?php
            echo HTML::link(
                action('\Project\Controllers\Issues\Index', array($issue->slug)),
                sprintf(_('Back to <q>%s</q>'), $issue->title)
            ) ?>
        </p>
    </div>
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
    HTML::link('', _('Home')),
    HTML::link(action('\Project\Controllers\Issues\Index'), _('Issues')),
    HTML::link(action('\Project\Controllers\Issues\Index', array($issue->slug)), $title),
    HTML::link(action('\Project\Controllers\Issues\Order', array($issue->slug)), _('Order'))
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
