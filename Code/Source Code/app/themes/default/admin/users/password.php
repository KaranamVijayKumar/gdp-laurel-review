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
    <p><?php echo _("Update the user's password.") ?></p>

<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">

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
$user_subtitle = _('Password');
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
