<?php
/*!
 * categories_edit.php v0.1
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
    <div class="layout ph-- pt mb">
        <div class="layout__item">
            <?php echo Form::label('name', _('Name')) ?>
            <?php
            echo Form::text(
                'name',
                h($item->name),
                array('class' => 'text-input 1/1', 'id' => 'name')
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo Form::label('guidelines', _('Guidelines')) ?>
            <?php
            echo Form::textarea(
                'guidelines',
                $item->guidelines,
                array(
                    'data-redactor-min_height' => '220',
                    'rows'                     => '10',
                    'class'                    => 'text-input 1/1 text-input--redactor text-input--redactor-frontend',
                    'id'                       => 'guidelines',
                    'placeholder'              => _('Insert guidelines here ...'),
                )
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo Form::label('size_limit', _('Size Limit')) ?>
            <?php
            echo Form::text(
                'size_limit',
                h($item->size_limit),
                array('class' => 'text-input 1/1', 'id' => 'size_limit')
            ) ?>
        </div>
        <div class="layout__item 1/2 palm-1/1 check-list">
            <h4 class="content-hero"><?php echo _('Price') ?></h4>
            <?php echo Form::label('amount', _('Amount')) ?>
            <?php
            echo Form::number(
                'amount',
                $item->amount,
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
            <?php echo Form::checkbox('status', 1, (bool)$item->status, array('id' => 'status')) ?>
            <?php echo Form::label('status', _('Active')) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt+">
            <?php
            echo Form::button(
                _('Save'),
                array('class' => 'btn 1/1', 'type' => 'submit')
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
        <div class="flag flag--small flag--responsive flag--rev">
            <div class="flag__body gamma pv--">
                <div class="media media--rev">
                    <div class="media__img">
                        <?php
                        echo Form::open(
                            array(
                                'action' => action(
                                    '\Project\Controllers\Admin\Submissions\Categories@delete',
                                    array($item->id)
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
                        echo HTML::link(
                            action('\Project\Controllers\Admin\Submissions\Categories'),
                            _('Categories')
                        ) ?>
                        /
                        <?php echo h($item->name) ?>
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
    <?php echo ws_redactor_assets('file', 'image', 'snippet', 'link'); ?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
