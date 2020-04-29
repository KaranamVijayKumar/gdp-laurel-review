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
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<h4 class=""><?php echo _('Password') ?></h4>
<p><?php echo _('Update your account password. This password is used also on the main site.')?></p>

<?php echo Form::open(array('errors' => $errors)) ?>
<div class="layout ph-- mb">

    <div class="layout__item ">
        <?php echo Form::label('current_password', _('Current Password')) ?>

        <?php
        echo Form::password(
            'current_password',
            array('class' => 'text-input 1/1', 'id' => 'current')
        ) ?>

    </div>
    <div class="layout__item pt">
        <?php echo Form::label('new_password', _('New Password')) ?>

        <?php
        echo Form::password(
            'new_password',
            array('class' => 'text-input 1/1', 'id' => 'new')
        ) ?>

    </div>
    <div class="layout__item pt-">
        <?php echo Form::label('verify_new_password', _('Verify New Password')) ?>

        <?php
        echo Form::password(
            'verify_new_password',
            array('class' => 'text-input 1/1', 'id' => 'verify')
        ) ?>

    </div>
    <div class="cf"></div>
    <div class="layout__item 1/5 palm-1/1 pt+">
        <?php echo Form::button(_('Update'), array('class' => 'btn 1/1', 'type'=>'submit')) ?>
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
            <?php echo _('Password') ?>
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
