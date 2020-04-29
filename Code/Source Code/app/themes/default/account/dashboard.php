<?php

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
// global content
?>
<?php require __DIR__ . '/../_global/notifications.php'; ?>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<div class="grid-list container grid-list--bordered grid-list--featured text--center u-mt0">
    <!--
    <?php
    if (has_access('submissions_index')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Submissions\Index') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm">
                <span class="icon-upload icon--giant"></span>
                <div class="grid-list__body">
                    <h3><?php echo _('My Submissions') ?></h3>
                    <p class="note u-mb0">
                        <?php echo _('Create or view your submissions.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    }
    if (has_access('subscriptions_index')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Subscriptions\Index') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm">
                <span class="icon-refresh icon--giant"></span>
                <div class="grid-list__body">
                    <h3><?php echo _('Subscriptions') ?></h3>
                    <p class="note u-mb0">
                        <?php echo _('Renew or subscribe to issues.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    } ?>
    -->
</div>
<h2><?php echo _('Profile') ?></h2>
<hr/>
<div class="grid-list container grid-list--bordered text--center">
    <!--
    <?php
    if (has_access('account_biography')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Account\Biography') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm">
                <span class="icon-briefcase icon--huge"></span>
                <div class="grid-list__body">
                    <h3><?php echo _('Biography') ?></h3>
                    <p class="note u-mb0">
                        <?php echo _('Update your biography.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    }
    if (has_access('account_email')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Account\Email') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm">
                <span class="icon-envelope icon--huge"></span>
                <div class="grid-list__body">
                    <h3><?php echo _('Name &amp; Email') ?></h3>
                    <p class="note u-mb0">
                        <?php echo _('Edit your name or email.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    }
    if (has_access('account_password')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Account\Password') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm">
                <span class="icon-key2 icon--huge"></span>
                <div class="grid-list__body">
                    <h3><?php echo _('Password') ?></h3>
                    <p class="note u-mb0">
                        <?php echo _('Update your password.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    }
    if (has_access('account_contact')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Account\Contact') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm">
                <span class="icon-gift icon--huge"></span>
                <div class="grid-list__body">
                    <h3><?php echo _('Address') ?></h3>
                    <p class="note u-mb0">
                        <?php echo _('Your shipping address.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    } ?>
    -->
</div>
<h2><?php echo _('Newsletter') ?></h2>
<hr/>
<div class="grid-list container grid-list--bordered">
    <!--
    <?php
    if (has_access('newsletter_index')) { ?>

         --><a href="<?php echo action('\Project\Controllers\Newsletter\Index') ?>" class="grid-list__item u-1-of-5 u-1-of-1-palm u-pv0">
                <div class="grid-list__body">
                    <h3 class=""><?php echo _('Newsletter') ?></h3>
                    <p class="note">
                        <?php echo _('Manage the newsletter subscription.') ?>
                    </p>
                </div>
            </a><!--

    <?php
    } ?>
    -->
</div>
<hr/>
<?php
if (has_access('account_delete')) { ?>

    <p class="note mb0">In order to completely delete your account follow the link below.
        You will need to provide the account's password.</p>
    <h5 class="text--negative mt0">
        <?php
        echo \Story\HTML::link(
            action('\Project\Controllers\Account\Delete'),
            _('Delete your account'),
            array('class' => 'text--negative')
        ) ?>
    </h5>
    <?php
} ?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
<div class="text--user">
    <?php echo $engine->getSection('page-aside'); ?>
</div>
<?php
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
    \Story\HTML::link(action('\Project\Controllers\Account\Dashboard'), $title),
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
