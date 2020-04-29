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
<?php echo Form::open(array('action'=>'chapbooksUpload.php','errors' => $errors, 'enctype' => 'multipart/form-data')) ?>
    <div class="layout ph-- mb">
        <div class="layout__item  2/3 palm-1/1 mt">
            <?php echo Form::label('title', _('Title')) ?>
            <?php
            echo Form::text(
                'title',
                '',
                array('class' => 'text-input 1/1', 'id' => 'title','required'=>'required')
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1 mt">
            <?php echo Form::label('status', _('Status')) ?>
            <?php
            echo Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                '0',
                array('id' => 'status')
            ) ?>
        </div>

        <div class="layout__item 1/2 palm-1/1">
            <h4 class="content-hero"><?php echo _('Upload created CSV-Template') ?></h4>
            <?php echo Form::label('csv_file', _('File')) ?>
            <?php echo Form::file('csv_file', array('id' => 'csv_file','required'=>'required')) ?>
            <p class="gray">
                <small>
                    <?php
                    printf(
                        _('You can upload CSV file files with the maximum size of %2$s.') . '<br>' .
                        _(
                            'Upload your prepared template file.'
                        ) . '<br>' .
                        _('The uploaded image will be resized automatically for better visitor experience.'),
                        '<strong>' . implode(', ', \Project\Models\IssueFile::$coverPageFileTypes) . '</strong>',
                        get_file_size(max_upload_size())
                    ); ?>
                </small>
            </p>
        </div><!--
     --><div class="layout__item 1/2 palm-1/1">
            <h4 class="content-hero"><?php echo _('Cover page image') ?></h4>
            <?php echo Form::label('file', _('File')) ?>
            <?php echo Form::file('file', array('id' => 'file','required'=>'required')) ?>
            <p class="gray">
                <small>
                    <?php
                    printf(
                        _('You can upload %1$s files with the maximum size of %2$s.') . '<br>' .
                        _(
                            'For best results make sure to upload only the cover image in portrait orientation.'
                        ) . '<br>' .
                        _('The uploaded image will be resized automatically for better visitor experience.'),
                        '<strong>' . implode(', ', \Project\Models\IssueFile::$coverPageFileTypes) . '</strong>',
                        get_file_size(max_upload_size())
                    ); ?>
                </small>
            </p>
        </div>
        <?php //require_once __DIR__ . '/partials/create_optional_sections.php' ?>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt-">
            <?php
            echo Form::button(
                _('CreateByFile'),
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
            <?php
            echo HTML::link(
                action('\Project\Controllers\Admin\Chapbooks\Index'),
                _('Chapbooks')
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
    <link rel="stylesheet" href="<?php echo to('themes/default/global/editor.css') ?>">

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
<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';

