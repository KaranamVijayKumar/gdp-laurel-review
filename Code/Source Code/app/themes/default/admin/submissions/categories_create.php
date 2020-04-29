<?php
/*!
 * categories_create.php v0.1
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
<?php echo \Story\Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- pt mb">
        <div class="layout__item">
            <?php echo \Story\Form::label('name', _('Name')) ?>
            <?php
            echo \Story\Form::text(
                'name',
                '',
                array('class' => 'text-input 1/1', 'id' => 'name')
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo \Story\Form::label('guidelines', _('Guidelines')) ?>
            <?php
            echo \Story\Form::textarea(
                'guidelines',
                '',
                array(
                    'rows'                    => '10',
                    'class'                   => 'text-input 1/1 text-input--redactor text-input--redactor-frontend',
                    'id'                      => 'guidelines',
                    'placeholder'             => _('Insert guidelines here ...'),
                )
            ) ?>

        </div>
        <div class="layout__item pt-">
            <?php echo \Story\Form::label('size_limit', _('Size Limit')) ?>
            <?php
            echo \Story\Form::text(
                'size_limit',
                '',
                array('class' => 'text-input 1/1', 'id' => 'size_limit')
            ) ?>

        </div>
        <div class="layout__item 1/2 palm-1/1 check-list">
            <h4 class="content-hero"><?php echo _('Price') ?></h4>
            <?php echo \Story\Form::label('amount', _('Amount')) ?>
            <?php
            echo \Story\Form::number(
                'amount',
                '',
                array(
                    'min'   => '0.01',
                    'max'   => '1000000000',
                    'step'  => '0.01',
                    'class' => 'text-input text-input--small',
                    'id'    => 'amount'
                )
            ) ?>

        </div><!--
     --><div class="layout__item 1/2 palm-1/1 check-list">
            <h4 class="content-hero"><?php echo _('Status') ?></h4>
            <?php echo \Story\Form::checkbox('status', 1, false, array('id' => 'status')) ?>
            <?php echo \Story\Form::label('status', _('Active')) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt+">
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
                action('\Project\Controllers\Admin\Submissions\Categories'),
                _('Categories')
            ) ?>
            /
            <?php echo _('New Category') ?>

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

include __DIR__ .'/../_masters/page.master.php';
