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
ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data')) ?>
    <div class="layout ph-- pt mb">
        <div class="layout__item 2/3 palm-1/1">
            <?php echo \Story\Form::label('slug', _('Name')) ?>
            <?php
            echo \Story\Form::text(
                'slug',
                '',
                array('class' => 'text-input 1/1', 'id' => 'slug', 'placeholder' => _('Host Name'))
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('status', _('Status')) ?>
            <?php
            echo \Story\Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                '1',
                array('id' => 'status', 'class' => 'chosen-select palm-1/1')
            ) ?>

        </div>
        <div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('Instagram Link', _('Instagram')) ?>
            <?php
            echo \Story\Form::text(
                'instalink',
                '',
                array('class' => 'text-input 1/1', 'id' => 'instalink', 'placeholder' => _('InstaLink'))
            ) ?>
        </div>

        <div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('Facebook Link', _('Facebook')) ?>
            <?php
            echo \Story\Form::text(
                'fbLink',
                '',
                array('class' => 'text-input 1/1', 'id' => 'instalink', 'placeholder' => _('Facebook link'))
            ) ?>
        </div>



        <div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('Twitter Link', _('Twitter')) ?>
            <?php
            echo \Story\Form::text(
                'twitter_link',
                '',
                array('class' => 'text-input 1/1', 'id' => 'twitter_link', 'placeholder' => _('Twitter_link'))
            ) ?>
        </div>

        <div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('WordPress Link', _('Wordpress')) ?>
            <?php
            echo \Story\Form::text(
                'wordPress',
                '',
                array('class' => 'text-input 1/1', 'id' => 'instalink', 'placeholder' => _('wordPress link'))
            ) ?>
        </div>

        <div class="layout__item 1/1">
            <?php echo \Story\Form::label('description', _('Description')) ?>
            <?php
            echo \Story\Form::text(
                'description',
                '',
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
            <?php echo \Story\Form::file('image_file', array('id' => 'file','required'=>'required')) ?>
            <p class="gray">
                <small>
                    You can upload jpeg, png, gif files with the maximum size of 2 MB.
                    For best results make sure to upload only the cover image in portrait orientation.
                    The uploaded image will be resized automatically for better visitor experience.
                </small>
            </p>

        </div>

    </div>




        <div class="layout__item pt-">
            <?php echo \Story\Form::label('content', _('Content')) ?>
            <?php
            echo \Story\Form::textarea(
                'content',
                '',
                array(
                    'rows'                    => '10',
                    'class'                   => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                      => 'guidelines',
                    'placeholder'             => _('Insert HostName content here ...')
                )
            ) ?>

        </div>

        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1">
            <?php
            echo \Story\Form::button(
                _('Create'),
                array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
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
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">

                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Pages\Aboutus'),
                    _('HostName')
                ) ?>
                /
                <?php echo _('New HostName') ?>

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
