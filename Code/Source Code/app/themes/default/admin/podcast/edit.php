<?php
/*!
 * create.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\Form;

ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data')) ?>
    <div class="layout ph-- pt mb">
        <div class="layout__item 2/3 palm-1/1">

            <?php echo \Story\Form::hidden('id', $snippet->id) ?>
            <?php echo \Story\Form::label('slug', _('Name')) ?>
            <?php
            echo \Story\Form::text(
                'slug',
                $snippet->slug,
                array('class' => 'text-input 1/1', 'id' => 'slug', 'placeholder' => _('My snippet'))
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('status', _('Status')) ?>
            <?php
            echo \Story\Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                $snippet->status,
                array('id' => 'status', 'class' => 'chosen-select palm-1/1')
            ) ?>

        </div>
        <div class="layout__item 1/1">
            <?php echo \Story\Form::label('description', _('Description')) ?>
            <?php
            echo \Story\Form::text(
                'description',
                $snippet->description,
                array(
                    'class' => 'text-input 1/1',
                    'id' => 'description',
                    'placeholder' => _('Enter a short description...')
                )
            ) ?>
        </div>

        <div class="layout__item 1/1 palm-1/1">
            <h4 class="content-hero"><?php echo _('Cover page image') ?></h4>
            <?php echo \Story\Form::label('image_file', _('image_file')) ?>
            <?php echo \Story\Form::file('image_file', array('id' => 'file','value'=>$snippet->profile_img_path)) ?>
            <p class="gray">
                <small>
                    You can upload jpeg, png, gif files with the maximum size of 2 MB.
                    For best results make sure to upload only the cover image in portrait orientation.
                    The uploaded image will be resized automatically for better visitor experience.
                </small>
            </p>

        </div>
        <div class="layout__item 1/1 palm-1/1">
            <h4 class="content-hero"><?php echo _('Audio Song') ?></h4>
            <?php echo \Story\Form::label('audio_file', _('audio_file')) ?>
            <?php echo \Story\Form::file('audio_file', array('id' => 'file','value'=>$snippet->audio_img_path)) ?>
            <p class="gray">
                <small>
                    You can upload MP3 files with the maximum size of 30 MB.
                </small>
            </p>
        </div>

        <div class="layout__item pt-">
            <?php echo \Story\Form::label('content', _('Content')) ?>
            <?php
            echo \Story\Form::textarea(
                'content',
                $snippet->attributes['content'],
                array(
                    'rows'                    => '10',
                    'class'                   => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                      => 'guidelines',
                    'placeholder'             => _('Insert snippet content here ...'),
                    'data-redactor-replace-divs' => 0,
                    'data-redactor-denied-tags' => "[]"
                )
            ) ?>

        </div>

        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1">
            <?php
            echo \Story\Form::button(
                _('Save'),
                array('class' => 'btn 1/1', 'type' => 'submit')
            ) ?>
        </div>
    </div>
<?php echo \Story\Form::close() ?>
<?php


$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive flag--rev">
            <div class="flag__body gamma pv--">
                <div class="media media--rev">
                    <div class="media__img">
                        <?php
                        echo Form::open(
                            array(
                                'action' => action(
                                    '\Project\Controllers\Admin\Pages\Podcast@delete',
                                    array($snippet->id)
                                ),
                                'method' => 'delete',
                                'class'  => 'filter'
                            )
                        ) ?>
                        <button class="btn btn--negative i-trash-o confirm" name="action" value="trash"
                                type="submit"> <?php echo _('Delete') ?></button>
                        <?php echo Form::close() ?>

                    </div>
                    <div class="media__body">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Admin\Pages\Podcast'),
                            _('Podcast')
                        ) ?>
                        /
                        <?php echo $snippet->slug ?>
                    </div>
                </div>
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
    <?php echo ws_redactor_assets('file', 'image', 'link'); ?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
