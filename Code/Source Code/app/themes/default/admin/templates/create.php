<?php
/*!
 * edit.php v0.1
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
<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">
        <div class="layout__item mt">
            <?php echo \Story\Form::label('default_template', _('Default template')) ?>
            <?php
            echo \Story\Form::select(
                'default_template',
                array('' => '') + $default_templates,
                false,
                array(
                    'id' => 'default_template',
                    'class' => 'chosen-select  1/3 lap-1/1 palm-1/1 js-fieldloader',
                    'data-placeholder' => _('Select a default template...'),
                    'data-fieldloader-url' => action('\Project\Controllers\Admin\Templates\Item')
                )
            ) ?>
            <p class="gray">
                <?php
                echo _('The new template will inherit the selected template\'s properties.') ?>
            </p>
        </div>
        <div class="layout__item">

                <?php echo Form::label('description', _('Description')) ?>
                <?php
                echo Form::text(
                    'description',
                    '',
                    array('class' => 'text-input 1/1 js-description', 'id' => 'description')
                ) ?>

        </div>
        <div class="layout__item">
            <?php echo Form::label('subject', _('Title / Subject')) ?>
            <?php
            echo Form::text(
                'subject',
                '',
                array('class' => 'text-input 1/1 js-subject', 'id' => 'subject')
            ) ?>

        </div>
        <div class="layout__item pt">
            <?php echo Form::label('message', _('Content / Message')) ?>
            <?php
            echo Form::textarea(
                'message',
                '',
                array(
                    'rows'                     => '10',
                    'class'                    => 'text-input 1/1 text-input--redactor js-message',
                    'id'                       => 'message',
                    'placeholder'              => _('Insert message here ...'),
                )
            ) ?>
        </div>
        <div class="layout__item ">
            <h4 class="content-hero"><?php echo _('Placeholders') ?></h4>
            <div class="js-placeholders--notice gray">
                <em><?php echo _('Please select a default template to load the placeholders.') ?></em>
            </div>
            <table class="1/1 js-placeholders visuallyhidden">
                <tbody></tbody>
            </table>
        </div>

        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 mt">
            <?php echo Form::button(_('Create'), array('class' => 'btn btn--positive 1/1', 'type' => 'submit')) ?>
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
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Templates\Index'),
                    _('Templates')
                ) ?>
                /
                <?php echo $title ?>
            </div>
        </div>
    </div>
<?php
$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
?>
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/redactor.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/editor.css') ?>">


<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <?php echo ws_redactor_assets('file', 'image', 'snippet', 'link'); ?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>

<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
