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
        <div class="layout__item">

            <?php
            if ($item->locked) { ?>

            <?php
            } else { ?>
                <p>
                    <?php echo Form::label('description', _('Description')) ?>
                    <?php
                    echo Form::text(
                        'description',
                        $item->description,
                        array('class' => 'text-input 1/1', 'id' => 'description')
                    ) ?>
                </p>
            <?php
            } ?>
        </div>
        <div class="layout__item">
            <?php echo Form::label('subject', _('Title / Subject')) ?>
            <?php
            echo Form::text(
                'subject',
                $item->subject,
                array('class' => 'text-input 1/1', 'id' => 'subject')
            ) ?>

        </div>
        <div class="layout__item pt">
            <?php echo Form::label('message', _('Content / Message')) ?>
            <?php
            echo Form::textarea(
                'message',
                $item->attributes['message'],
                array(
                    'rows'                     => '10',
                    'class'                    => 'text-input 1/1 text-input--redactor',
                    'id'                       => 'guidelines',
                    'placeholder'              => _('Insert message here ...'),
                )
            ) ?>
        </div>
        <?php
        if ($item->variables) { ?>
            <div class="layout__item ">
                <h4 class="content-hero"><?php echo _('Placeholders') ?></h4>
                <table class="1/1">
                    <tbody>
                <?php
                    foreach ($item->variables as $key => $description) { ?>
                        <tr>
                            <td class="1/5 purple"><?php echo h($key) ?></td>
                            <td><?php echo _($description) ?></td>
                        </tr>

                <?php
                    } ?>
                    </tbody>
                </table>
            </div>
        <?php
        } ?>
        <div class="layout__item">
            <p class="gray content-hero">
                <em>
                    <?php echo sprintf(_('ID: %s'), $item->type .'.' .$item->name) ?>
                </em>
            </p>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1">
            <?php echo Form::button(_('Save'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
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
                        if ($item->locked) { ?>
                            <span class="btn btn--label"><?php echo h($item->type) ?></span>
                            <span class="i--delta i-lock" title="<?php echo _('Default template') ?>"></span>
                        <?php
                        } else { ?>
                            <?php
                            echo Form::open(
                                array(
                                    'action' => action(
                                        '\Project\Controllers\Admin\Templates\Delete',
                                        array($item->id)
                                    ),
                                    'method' => 'delete',
                                    'class'  => 'filter'
                                )
                            ) ?>
                            <span class="btn btn--label"><?php echo h($item->type) ?></span>
                            <button class="btn btn--negative i-trash-o confirm" name="action" value="trash"
                                    type="submit"> <?php echo _('Delete') ?></button>
                            <?php echo Form::close() ?>
                        <?php
                        } ?>

                    </div>
                    <div class="media__body">
                        <?php echo HTML::link(action('\Project\Controllers\Admin\Templates\Index'), $title) ?>
                        /
                        <?php echo h($item->description) ?>
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

<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
