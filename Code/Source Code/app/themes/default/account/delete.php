<?php
/*!
 * delete.php v0.1
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
echo Form::open(array('errors'=>$errors, 'method' => 'delete'));
?>
<div class="notifications">
    <ol>
        <li class="notification notification--negative">
            <div class="notification__img icon-caution"></div>
            <div class="notification__body">
                <?php echo _('This action will permanently remove your account!
                 This action is instant and cannot be recovered.') ?>
            </div>

        </li>
    </ol>
</div>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

    <p>
        <?php echo Form::label('password', _('Current Password')) ?>

        <?php
        echo Form::password(
            'password',
            array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'password')
        ) ?>

    </p>
    <p>
        <?php
        echo Form::button(
            _('Delete Account'),
            array('class' => 'btn btn--negative confirm u-1-of-1-palm', 'type'=>'submit')
        ) ?>

    </p>
    <hr/>
    <p>
        <?php echo HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Back to Account')) ?>

    </p>
    <div class="text--user">
        <?php echo $engine->getSection('page-footer'); ?>
    </div>
<?php
echo Form::close();
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
    HTML::link('', _('Home')),
    HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    HTML::link(action('\Project\Controllers\Account\Delete'), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
