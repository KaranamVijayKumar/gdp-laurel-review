<?php
/*!
 * index.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

require_once __DIR__ . '/../issues/sections.php';

/** @var StoryEngine\StoryEngine $engine */

// --------------------------------------------------------------
// Title
// --------------------------------------------------------------

use Story\HTML;

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
            <span class="icon-refresh icon--huge"></span>
        </div>
        <div class="flag__body">
            <h2 class="u-mv0 text--alert"><?php echo $title ?></h2>

            <p class="note u-mt0">
                Subscribe to our issues.
            </p>
        </div>
    </div>
    <hr/>
    <div class="text--user mb">
        <?php echo $engine->getSection('page-content'); ?>
    </div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

    <div class="notifications">
        <ol>
            <li class="notification">
                <div class="notification__img icon-sad"></div>
                <div class="notification__body">
                    <?php echo _("Currently you do not have an issue subscription.") ?>
                </div>

            </li>
        </ol>
    </div>
<?php include __DIR__ . '/subscribe.partial.php' ?>


    <div class="text--user">
        <?php echo $engine->getSection('page-footer'); ?>
    </div>
    <hr/>
    <p>
        <?php echo \Story\HTML::link(action('\Project\Controllers\Subscriptions\Index'), _('Back to Subscriptions')) ?>

    </p>

<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
<?php include 'category_prices.partial.php' ?>

<?php
echo $engine->getSection('aside-last-issue');
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
    HTML::link(action('\Project\Controllers\Subscriptions\Index'), _('Subscriptions')),
    HTML::link(action('\Project\Controllers\Subscriptions\Create'), $title),
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ . '/../_masters/page.master.php';
