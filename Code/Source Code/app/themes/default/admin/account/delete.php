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
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<h4 class=""><?php echo _('Delete Account') ?></h4>
<p>
    <?php
    echo _(
        'This action will permanently remove your account!'.
        'This action is instant and cannot be recovered.'
    )?>
</p>
<p><?php echo _('Please provide your account password to proceed.') ?></p>
<?php echo Form::open(array('errors' => $errors, 'method' => 'delete')) ?>
    <div class="layout ph-- mb">

        <div class="layout__item ">
            <?php echo Form::label('password', _('Current Password')) ?>

            <?php
            echo Form::password(
                'password',
                array('class' => 'text-input 1/1', 'id' => 'password')
            ) ?>

        </div>
        <div class="layout__item 1/5 palm-1/1 pt+">
            <?php
            echo Form::button(
                _('Delete Account'),
                array(
                    'class' => 'btn 1/1 btn--negative confirm',
                    'type'=>'submit',
                    'data-confirm' => _('This action is permanent and cannot be recovered.')
                )
            ) ?>
        </div>
    </div>
<?php
echo Form::close();
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
            <span class="red"><?php echo _('Delete Account') ?>
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

include __DIR__ .'/../_masters/page.master.php';
