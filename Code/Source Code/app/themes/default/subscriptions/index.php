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
            <h2 class="u-mv0"><?php echo $title ?></h2>

            <p class="note u-mt0">
                Renew or subscribe to issues.
            </p>
        </div>
    </div>
    <hr/>
    <div class="text--user u-mb">
        <?php echo $engine->getSection('page-content'); ?>
    </div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<?php
if ($subscription) { ?>

    <h3><?php echo _('Current subscription') ?></h3>
    <?php include __DIR__ . '/current_subscription.partial.php' ?>


<?php
}
if ($canCreateSubscription) { ?>

    <h3 class="text--alert"><?php echo _('Subscribe') ?></h3>
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

<?php
} ?>

<?php
if ($upcomingSubscription) { ?>

    <h3><?php echo _('Upcoming subscription') ?></h3>
    <?php include __DIR__ . '/upcoming_subscription.partial.php' ?>
    <?php
} ?>

<?php if (count($subscriptions)) { ?>

    <h3><?php echo _('Previous subscriptions') ?></h3>
    <?php include __DIR__ . '/subscriptions.partial.php' ?>

<?php
} ?>
    <div class="text--user">
        <?php echo $engine->getSection('page-footer'); ?>
    </div>
    <hr/>
    <p>
        <?php echo \Story\HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Back to Account')) ?>

    </p>

<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
<?php
if ($categories && ($canCreateSubscription || $canRenewSubscription)) {
    include 'category_prices.partial.php';
}
?>
<div class="text--user">
    <?php echo $engine->getSection('page-aside'); ?>
</div>
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
    HTML::link(action('\Project\Controllers\Subscriptions\Index'), $title),
);

// --------------------------------------------------------------
// Overrides
// --------------------------------------------------------------
$palm_hidden = 1;

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';

