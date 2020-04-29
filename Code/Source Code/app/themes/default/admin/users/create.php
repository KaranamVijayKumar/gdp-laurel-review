<?php
/*!
 * create.php v0.1
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

    <h4 class="content-hero"><?php echo _('Name and Email address') ?></h4>
    <p><?php echo _('Enter the name and a valid email address.') ?></p>

<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">
        <div class="layout__item">
            <?php echo Form::label('name', _('Name')) ?>
            <?php echo Form::text('name', '', array('class' => 'text-input 1/1', 'id' => 'name')) ?>
        </div>
        <div class="layout__item pt">
            <?php echo Form::label('email', _('Email address')) ?>
            <?php
            echo Form::text(
                'email',
                '',
                array('class' => 'text-input 1/1', 'id' => 'email')
            ) ?>
            <?php echo Form::label('verify_email', _('Verify Email address')) ?>
            <?php
            echo Form::text(
                'verify_email',
                '',
                array('class' => 'text-input 1/1', 'id' => 'verify_email')
            ) ?>
        </div>
        <div class="layout__item">
            <h4 class="content-hero"><?php echo _('Password') ?></h4>
            <p><?php echo _('Make sure the password contains at least 8 characters.') ?></p>
        </div>
        <div class="layout__item mb-">
            <?php echo Form::label('password', _('Password')) ?>
            <?php
            echo Form::password(
                'password',
                array('class' => 'text-input 1/1', 'id' => 'password')
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo Form::label('verify_password', _('Verify Password')) ?>
            <?php
            echo Form::password(
                'verify_password',
                array('class' => 'text-input 1/1', 'id' => 'verify_password')
            ) ?>
        </div>
        <div class="layout__item 1/2 palm-1/1 check-list">
            <h4 class="content-hero"><?php echo _('Roles') ?></h4>
            <p><?php echo _('Default roles were automatically selected.') ?></p>
            <div class="form-fields p0">
                <ul class=" check-list p0">
                    <?php
                    foreach ($roles as $id => $role) { ?>
                        <li>
                            <?php
                            echo Form::checkbox(
                                'roles[]',
                                $id,
                                in_array($id, $defaultRoles),
                                array('id' => 'role_' . $id)
                            ) ?>

                            <?php echo Form::label('role_' . $id, $role) ?>
                        </li>
                    <?php
                    } ?>
                </ul>
            </div>
        </div><!--
     --><div class="layout__item 1/2 palm-1/1 check-list">
            <h4 class="content-hero"><?php echo _('Status') ?></h4>
            <p>
                <?php
                echo _(
                    'An active user will receive a welcome email, '.
                    'while an inactive one will receive an activation email.'
                ) ?>
            </p>
            <?php echo Form::checkbox('active', 1, false, array('id' => 'active')) ?>
            <?php echo Form::label('active', _('Active')) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt+">
            <?php
            echo Form::button(
                _('Create'),
                array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
            ) ?>
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
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <?php echo HTML::link(action('\Project\Controllers\Admin\Users\Index'), _('Users')) ?>
                /
                <?php echo _('New User') ?>
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
