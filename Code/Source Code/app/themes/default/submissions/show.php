<?php
/*!
 * show.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\HTML;

// --------------------------------------------------------------
// Title
// --------------------------------------------------------------
if (!isset($title)) {
    $title = h($main_page_content->title);
}

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-upload icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><q><?php echo h($submission->name) ?></q></h2>
        <p class="note u-mt0">
            Submission details.
        </p>
    </div>
</div>
<hr/>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<?php include_once 'properties.partial.php' ?>

<?php include_once 'coverletter.partial.php' ?>

<?php if ($withdrawURL) { ?>

    <h3>
        <?php echo _('Withdraw') ?>
    </h3>

    <p>
        <?php
        echo HTML::link(
            $withdrawURL,
            'Withdraw a part or entire submission',
            array('class' => 'btn btn--negative u-1-of-1-palm')
        ); ?>
    </p>
    <?php
        if ($partial_withdrawn) { ?>
            <p class="u-mb- content-hero content-hero--secondary"><?php echo _('Withdrawals') ?></p>
            <div class="u-mb- text--user">
                <?php echo str_replace(array("\n"), "<br>", $partial_withdrawn->content) ?>
            </div>

        <?php
        } ?>

<?php } ?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
    <hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Submissions\Index'), _('Back to My Submissions')) ?>

</p>
<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();

if ($signURL) { ?>
    <h3>Sign the submission</h3>
    <div class="notifications">
        <ol>
            <li class="notification">
                <div class="notification__img icon-caution"></div>
                <div class="notification__body">
                    <?php echo _('In order for us to publish your submission you need to sign the release form.') ?>
                </div>

            </li>
        </ol>
    </div>
    <p>
        <?php echo HTML::link($signURL, _('Sign this submission'), array('class' => 'btn u-1-of-1'));?>
    </p>

    <?php
}
?>
<div class="text--user">
    <?php echo $engine->getSection('page-aside'); ?>
</div>
<?php
include "create_promo.partial.php";

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
    HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    HTML::link(action('\Project\Controllers\Submissions\Index'), _('My Submissions')),
    HTML::link(
        action('\Project\Controllers\Submissions\Show', array($submission->id)),
        html2text($submission->name)
    )
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
