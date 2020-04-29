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

<?php echo Form::open(array('errors' => $errors, 'class' => 'filter')) ?>
    <ul class="tabs-content">
        <li id="tab1" class="mv-">
            <?php
            /** @var \Story\Collection $page_content */
            echo Form::textarea(
                'required-section-content',
                $page_content->findBy('name', 'content', $default_content)->attributes['content'],
                array(
                    'rows'                     => '20',
                    'class'                    => 'text-input 1/1 text-input--redactor text-input--redactor-frontend',
                    'id'                       => 'required-section-content',
                    'placeholder'              => _('Insert content here ...'),
                    'data-toolbar-external'    => '#toolbar1'
                )
            ) ?>

        </li>
        <li id="tab2" class="pt-">
            <?php require_once __DIR__ . '/edit_extra_sections.partial.php' ?>
            <?php require_once __DIR__ . '/edit_optional_sections.partial.php' ?>
        </li>
        <li id="tab3">
            <?php require_once __DIR__ . '/edit_info.partial.php' ?>
        </li>
    </ul>
    <div class="1/4 lap-1/3 palm-1/1 mb">
        <?php
        echo \Story\Form::button(
            _('Save'),
            array('class' => 'btn 1/1', 'type' => 'submit')
        ) ?>
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
        <div class="flag flag--rev flag--editable">
            <a href="#" class="flag__img i-edit" data-edit="page-title-en" data-edit-url="<?php
            echo action('\Project\Controllers\Admin\Pages\Edit@title', array($page->id)) ?>"></a>
            <h2 class="flag__body" id="page-title-en">
                <?php echo html2text($page_content->findBy('name', 'content', $default_content)->title) ?>
            </h2>
        </div>
        <div class="flag flag--rev flag--editable flag--secondary">

            <div class="flag__body">
                <?php
                if ($page->locked) { ?>
                    <small><?php echo \Story\URL::to($page->slug) ?></small>
                <?php
                } else { ?>
                    <small><?php echo \Story\URL::to('') ?></small><small id="page-url"><?php
                        echo $page->slug ?></small>
                    <a href="#" class="i-edit flag__img--tiny" data-edit="page-url" data-edit-url="<?php
                    echo action('\Project\Controllers\Admin\Pages\Edit@slug', array($page->id)) ?>">&nbsp;</a>
                <?php
                } ?>

            </div>

        </div>
        <div class="flag flag--rev flag--small flag--responsive ">
            <div class="flag__img mb0 actions">
                <?php
                echo $page->locked ? '' : Form::open(
                    array(
                        'action' => action('\Project\Controllers\Admin\Pages\Delete', array($page->id)),
                        'class' => 'filter'
                    )
                ) ?>
                <ul class="nav mv- actions">
                    <li>
                    <?php
                    if ($page->locked) { ?>

                        <span class="i-lock btn--disabled" title="<?php
                        echo _('Page is locked, cannot be deleted.') ?>"></span>

                    <?php
                    } else { ?>

                        <?php
                        echo Form::button(
                            '',
                            array(
                                'class' => 'i-trash-o nowrap action--negative confirm',
                                'type' => 'submit',
                                'title' => _('Delete this page')
                            )
                        ) ?>

                    <?php
                    } ?>
                    </li>
                </ul>
                <?php echo $page->locked ? '' : Form::close() ?>
            </div>
            <div class="flag__body">
                <ul class="tabs mv-">
                    <li class="tabs__item">
                        <a href="#tab1" class="tabs__link" data-showtoolbar="toolbar1"><?php echo _('Content') ?></a>
                    </li>
                    <li class="tabs__item">
                        <a href="#tab2" class="tabs__link"><?php echo _('Extra') ?></a>
                    </li>
                    <li class="tabs__item">
                        <a href="#tab3" class="tabs__link"><?php echo _('Info') ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="toolbar1" class="redactor-toolbar redactor-toolbar--active"></div>
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
