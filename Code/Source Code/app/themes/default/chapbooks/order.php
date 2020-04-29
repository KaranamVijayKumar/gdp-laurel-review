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

ob_start();
?>
<div class="media media--responsive">
    <div class="media__img palm--hidden u-1-of-5 u-1-of-1-palm text--center">
        <?php include __DIR__ .'/chapbook_aside.partial_wo_order.php'; ?>
    </div>
    <div class="media__body">
        <!-- .hero -->
        <div class="hero hero--secondary palm--hidden">
            <!-- .hero-issue -->
            <div class="flag hero-issue ph0">
                <div class="flag__body hero-issue__body">
                    <h2 class="mt- palm--hidden">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)),
                            h($chapbook->title)
                        ) ?>
                    </h2>
                    <div class="hero-issue__description  text--secondary">
                        <?php echo $engine->getSection('chapbook-short_description'); ?>
                    </div>
                </div>
            </div><!-- /.hero-issue -->
        </div><!-- /.hero -->

        <!-- price -->
        <div class="flag flag--rev">
            <div class="flag__img gamma"><?php echo money_format('%n', $price) ?></div>
            <div class="flag__body">
                <h3 class="u-mb"><?php echo sprintf(_('Chapbook <q>%s</q>'), $chapbook->title) ?></h3>
            </div>
        </div>
        <hr class="mt0"/>
        <!-- /price -->

        <?php require __DIR__ . '/../_global/notifications.php'; ?>

        <?php
        if (app('cart') && $in_stock) { ?>

            <?php echo \Story\Form::open(array('errors'=>$errors)) ?>

            <div class="cf"></div>
            <?php echo Form::hidden('chapbook', e($chapbook->title)) ?>
            <p class="text--right">
                <?php
                echo \Story\Form::button(
                    _('Add to Cart'),
                    array('type' => 'submit', 'class' => 'btn btn--alert u-1-of-1-palm')
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
            echo \Story\HTML::link(
                action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)),
                sprintf(_('Back to <q>%s</q>'), $chapbook->title)
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
    \Story\HTML::link('', _('Home')),
    \Story\HTML::link(action('\Project\Controllers\Chapbooks\Index'), _('Chapbooks')),
    \Story\HTML::link(action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)), $title),
    \Story\HTML::link(action('\Project\Controllers\Chapbooks\Order', array($chapbook->slug)), _('Order'))
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
