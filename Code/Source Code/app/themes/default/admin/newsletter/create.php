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
<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mv">
        <div class="layout__item 2/3 palm-1/1">
            <?php echo Form::label('subject', _('Subject')) ?>
            <?php
            echo Form::text(
                'subject',
                '',
                array('class' => 'text-input 1/1', 'id' => 'subject')
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo Form::label('status', _('Status')) ?>
            <?php
            echo Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                '0',
                array('id' => 'status')
            ) ?>
        </div>
        <div class="layout__item 1/1 pt">
            <?php echo Form::label('content', _('Content')) ?>
            <?php
            echo Form::textarea(
                'content',
                '',
                array(
                    'rows'                     => '5',
                    'class'                    => 'text-input 1/1 text-input--redactor',
                    'id'                       => 'content',
                    'placeholder'              => _('Insert content here ...')
                )
            ) ?>
        </div>
        <div class="layout__item mb">
            <?php require_once __DIR__ .'/template_selector.partial.php' ?>
        </div>
        <div class="layout__item mb-">
            <?php
            echo Form::label('notes', _('Notes') . ' <small class="additional">(' . _('Optional') . ')</small>') ?>
            <?php
            echo Form::text(
                'notes',
                '',
                array('class' => 'text-input 1/1', 'id' => 'notes')
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1">
            <?php echo Form::button(_('Create'), array('class' => 'btn btn--positive 1/1', 'type' => 'submit')) ?>
        </div>
        <div class="layout__item">
            <p class="mb0 gray">
                <?php
                echo _(
                    'Newsletters are <strong class="orange">not</strong> sent out instantly, instead'.
                    ' they are put in a queue system which is processed on a daily basis.'
                ) ?>
            </p>
            <p class="gray mv0">
                <?php
                echo _(
                    'Only enabled newsletters are sent out with the queue system.'
                ) ?>
            </p>
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
                <?php echo HTML::link(action('\Project\Controllers\Admin\News\Newsletter'), _('Newsletter')) ?>
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
