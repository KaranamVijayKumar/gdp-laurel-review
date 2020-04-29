<?php
/*!
 * email.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\Form;
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
echo Form::open(array('errors'=>$errors)) ?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-envelope icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><?php echo $title ?></h2>
        <p class="note u-mt0">
            Edit your name and email address.
            When you are changing the email make sure you enter in the verification field also.
        </p>
    </div>
</div>
<hr/>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<div class="u-mt">
    <?php include __DIR__ .'/email_fields.partial.php' ?>
</div>
<p>
    <?php echo Form::label('verify_email', _('Verify Email address')) ?>

    <?php echo Form::text('verify_email', '', array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'verify')) ?>
</p>
<p class="">
    <?php echo Form::button(_('Save'), array('type' => 'submit', 'class' => 'btn')) ?>
</p>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
<?php echo Form::close() ?>
    <hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Back to Account')) ?>

</p>
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
    \Story\HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    \Story\HTML::link(action('\Project\Controllers\Account\Email'), $title),
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
