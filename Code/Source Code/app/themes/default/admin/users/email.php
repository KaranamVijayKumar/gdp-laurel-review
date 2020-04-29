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
// $global_content
// --------------------------------------------------------------

ob_start();
?>
    <h4 class=""><?php echo _('Name and Email Address') ?></h4>
    <p><?php
        echo _(
            'Edit  the name and email address. When you are changing the email'.
            'make sure you enter in the verification field also.'
        ) ?>
    </p>

<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">
        <div class="layout__item ">
            <?php echo Form::label('name', _('Name')) ?>
            <?php
            echo Form::text(
                'name',
                $user->profiles->findBy('name', 'name')->value,
                array('class' => 'text-input 1/1', 'id' => 'name')
            ) ?>
        </div>
        <div class="layout__item pt">
            <?php echo Form::label('email', _('Email address')) ?>
            <?php
            echo Form::text(
                'email',
                $user->email,
                array('class' => 'text-input 1/1', 'id' => 'email')
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo Form::label('verify_email', _('Verify Email address')) ?>
            <?php
            echo Form::text(
                'verify_email',
                '',
                array('class' => 'text-input 1/1', 'id' => 'verify_email')
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php echo Form::button(_('Update'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
        </div>
    </div>
<?php echo Form::close() ?>

<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
$user_subtitle = _('Name and Email Address');
require_once __DIR__ . '/toolbar.partial.php';
$global_toolbar = ob_get_clean();

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
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
