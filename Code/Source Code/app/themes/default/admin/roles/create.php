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
<?php echo Form::open() ?>
    <div class="layout ph-- mv">
        <div class="layout__item">
            <?php echo Form::label('name', _('Name')) ?>
            <?php echo Form::text('name', '', array('class' => 'text-input 1/1', 'id' => 'name')) ?>
        </div>
        <div class="layout__item pt">
            <?php require __DIR__ . '/permissions.php' ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt+">
            <?php echo Form::button(_('Create'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
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
                <?php echo HTML::link(action('\Project\Controllers\Admin\Roles\Index'), _('Permissions')) ?>
                /
                <?php echo _('New') ?>
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
