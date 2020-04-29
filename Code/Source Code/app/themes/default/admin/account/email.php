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
    <h4 class=""><?php echo _('Name and Email address') ?></h4>
    <p>
        <?php
        echo _(
            'Edit your name and email address. When you are changing the email' .
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
                $user->profiles->findBy('name', 'name', $default)->value,
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
                array('class' => 'text-input 1/1', 'id' => 'verify')
            ) ?>

        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php echo Form::button(_('Save'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
        </div>
    </div>
<?php echo Form::close() ?>

<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--rev">
            <div class="flag__img">
                <?php echo HTML::gravatar($user->email, 32, '', 'mm'); ?>
            </div>
            <div class="flag__body gamma pv--">
                <?php echo HTML::link(action('\Project\Controllers\Admin\Account\Dashboard'), $title) ?>
                /
                <?php echo _('Name and Email address') ?>
            </div>
        </div>
    </div>

<?php
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
