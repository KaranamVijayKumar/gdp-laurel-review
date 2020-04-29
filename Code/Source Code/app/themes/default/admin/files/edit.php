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

/** @var \Project\Models\PublicAsset $file */

ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors, 'class'  => 'filter')) ?>
    <div class="layout ph-- pt mb">
        <div class="layout__item">
            <div class="flag flag--responsive">
                <?php
                if ($file->status) { ?>
                    <a class="flag__img 1/5 palm-1/1 c" href="<?php echo $file->url() ?>">
                        <?php
                        echo $file->getPreview(
                            'i-',
                            array(
                                'class' =>'generic-img 1/1',
                                'title' => $file->name
                            ),
                            'i--preview ' . ($file->status ? 'black' : 'gray')
                        ) ?>

                    </a>
                <?php
                } else { ?>
                    <div class="flag__img 1/5 palm-1/1 c">
                        <?php
                        echo $file->getPreview(
                            'i-',
                            array(
                                'class' =>'generic-img 1/1',
                                'title' => $file->name
                            ),
                            'i--preview ' . ($file->status ? 'black' : 'gray')
                        ) ?>

                    </div>
                <?php
                } ?>
                <div class="flag__body">
                    <h4 class="mv0">
                        <?php echo $file->name ?>
                    </h4>
                    <p class="gray mv0">
                        <?php
                        if ($file->status) { ?>
                            <?php echo get_file_size($file->getFileSize()) ?>
                            <br/>
                            <a class="i-download" href="<?php echo $file->url() ?>">
                                <?php echo _('Download') ?>
                            </a>
                        <?php
                        } ?>
                    </p>
                    <p class="check-list mt">
                        <?php
                        echo \Story\Form::select(
                            'status',
                            array('1' => _('Enabled'), '0' => _('Disabled')),
                            $file->status,
                            array('id' => 'status', 'class' => 'chosen-select palm-1/1')
                        ) ?>

                        <?php echo Form::button(_('Save'), array('type' => 'submit', 'class' => 'btn btn--tiny')) ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="layout__item mt">
            <table class="1/1 mb">
                <tbody>
                <?php
                if ($file->status) { ?>
                    <tr>
                        <td class="1/4"><?php echo _('URL') ?></td>
                        <td>
                            <?php echo h($file->url()) ?>
                        </td>
                    </tr>
                <?php
                } ?>
                <tr>
                    <td class="1/4"><?php echo _('Uploaded') ?></td>
                    <td>
                        <?php
                        echo $file->created->diffForHumans(
                        ) ?> <?php echo '(' . $file->created->toDayDateTimeString() . ')' ?>
                    </td>
                </tr>
                <?php
                if ($file->attributes['modified']) { ?>
                    <tr>
                        <td class="1/4"><?php echo _('Last modified') ?></td>
                        <td>
                            <?php echo $file->modified->diffForHumans() ?>
                            <?php echo '(' . $file->created->toDayDateTimeString() . ')' ?>
                        </td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
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
                                    '\Project\Controllers\Admin\Files\Delete',
                                    array($file->id)
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
                            action('\Project\Controllers\Admin\Files\Index'),
                            _('Files')
                        ) ?>
                        /
                        <?php echo $file->name ?>
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
<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
