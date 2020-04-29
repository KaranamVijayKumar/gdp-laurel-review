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

/** @var \Project\Models\Export $item */

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors)) ?>

    <div class="layout">
        <div class="layout__item">
            <h4 class="content-hero"><?php echo _('Name and Description') ?></h4>
        </div>
        <div class="layout__item 2/3 palm-1/1">
            <?php echo \Story\Form::label('name', _('Name')) ?>
            <?php
            echo \Story\Form::text(
                'name',
                $item->name,
                array('class' => 'text-input 1/1', 'id' => 'name', 'placeholder' => 'Submisssion exporter')
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1 mt0">
            <?php echo \Story\Form::label('status', _('Status')) ?>
            <?php
            echo \Story\Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                (int) $item->status,
                array('id' => 'status', 'class' => 'palm-1/1')
            ) ?>

        </div>
        <div class="layout__item">
                <?php
                echo Form::label(
                    'description',
                    _('Description') . ' <small class=additional>(' . _('Optional') . ')</small>'
                ) ?>
                <?php
                echo Form::textarea(
                    'description',
                    $item->description,
                    array(
                        'rows'                    => '2',
                        'class'                   => 'text-input 1/1 text--secondary',
                        'id'                      => 'description',
                        'placeholder'             => _('Enter a short description.'),
                        'style'                   => 'min-height:111px'
                    )
                ) ?>
        </div>
        <div class="layout__item mb">


            <div class="js-show-details mh--">

                <?php echo Form::label('exporter', _('Type')) ?>
                <?php
                echo \Story\Form::select(
                    'exporter',
                    array('0' => 'Select type') + $exporter->getExporterNames(),
                    $exporter_type,
                    array('id' => 'exporter', 'class' => 'palm-1/1 js-show-details__selector')
                ) ?>
                <h4 class="content-hero mt-"><?php echo _('Add columns') ?></h4>
                <!-- container -->
                <div class="js-show-details__container">
                    <div class="js-show-details__0">
                        <p class="gray mb0">
                            <em>
                            <?php echo _('Please select a type in order to add columns.') ?>
                            </em>
                        </p>
                    </div>
                    <?php
                    $__fields = app('session')->get('__fields', array('columns' => $item->columns));
                    foreach ($exporter->getExporterColumns() as $e => $columns) { ?>

                        <div class="js-show-details__<?php echo $e ?> visuallyhidden">
                            <div class="js-multiple mb">
                            <?php
                                if (count($__fields['columns']) && $e === $exporter_type) { ?>

                            <?php
                                    foreach ($__fields['columns'] as $i => $col) { ?>

                                        <div class="media mv-- media--small media--responsive">
                                            <div class="media__body">
                                                <div class="flag flag--small flag--rev">
                                                    <a href="#" class="flag__img pt i-times i--middle red js-multiple-remove"></a>
                                                    <div class="flag__body">
                                                        <?php echo Form::label('columns_' . ($i + 1), _('Column_' . ($i + 1))) ?>
                                                        <?php
                                                        echo \Story\Form::select(
                                                            'columns[]',
                                                            $columns,
                                                            $col,
                                                            array('id' => 'columns_%', 'class' => '')
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                            <?php
                                    } ?>

                            <?php
                                } ?>
                                <template class="hidden">
                                    <div class="media mv-- media--small media--responsive">
                                        <div class="media__body">
                                            <div class="flag flag--small flag--rev">
                                                <a href="#" class="flag__img pt i-times i--middle red js-multiple-remove"></a>
                                                <div class="flag__body">
                                                    <?php echo Form::label('columns_%', _('Column_%')) ?>
                                                    <?php
                                                    echo \Story\Form::select(
                                                        'columns[]',
                                                        $columns,
                                                        null,
                                                        array('id' => 'columns_%', 'class' => '')
                                                    ) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <a href="#" class="i-plus green js-multiple-add" title="<?php echo _('Add Column') ?>">
                                    <?php echo _('Add column') ?>
                                </a>
                            </div>
                        </div>

                    <?php
                    } ?>

                </div>
            </div>
        </div>
        <div class="cf"></div>
        <div class="layout__item mb 1/5 lap-1/3 palm-1/1">
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
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <div class="media media--rev">
                    <div class="media__img">
                        <?php
                        if (has_access('admin_exporters_delete')) { ?>
                            <?php echo Form::open(
                                array(
                                    'method' => 'delete',
                                    'class' => 'filter',
                                    'action' => action('\Project\Controllers\Admin\Exporters\Delete', array($item->id))
                                )
                            ) ?>
                            <button class="btn btn--negative confirm i-trash-o" name="action" value="trash"
                                    type="submit" data-confirm="<?php echo _('This action cannot be recovered.') ?>">
                                <?php echo _('Delete') ?></button>
                            <?php echo Form::close() ?>
                            <?php
                        } ?>
                    </div>
                    <div class="media__body">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Admin\Exporters\Index'),
                            _('Exporters')
                        ) ?>
                        /
                        <?php echo $item->name ?>
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
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">
<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
