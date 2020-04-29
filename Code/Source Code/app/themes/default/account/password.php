<?php
/*!
 * password.php v0.1
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
?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-key2 icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><?php echo $title ?></h2>
        <p class="note u-mt0">
            <?php echo _('Update your account password.') ?>
        </p>
    </div>
</div>
<hr/>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php echo Form::open(array('errors'=>$errors)) ?>

<?php require __DIR__ . '/../_global/notifications.php'; ?>

<p>
    <?php echo Form::label('current_password', _('Current Password')) ?>

    <?php
    echo Form::password('current_password', array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'current')) ?>

</p>
<p class="pt">
    <?php echo Form::label('new_password', _('New Password')) ?>

    <?php
    echo Form::password(
        'new_password',
        array('class' => 'text-input  u-2-of-3 u-1-of-1-palm', 'id' => 'new', 'placeholder' => _('Min. 8 characters'))
    ) ?>

</p>
<p>
    <?php echo Form::label('verify_new_password', _('Verify New Password')) ?>

    <?php
    echo Form::password(
        'verify_new_password',
        array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'verify')
    ) ?>
</p>
<p>
    <?php echo Form::button(_('Change Password'), array('class' => 'btn', 'type'=>'submit')) ?>
</p>

<?php echo Form::close() ?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
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
    \Story\HTML::link(action('\Project\Controllers\Account\Password'), $title),
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
