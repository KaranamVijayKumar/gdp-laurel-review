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
    <div class="layout ph-- mv">
        <div class="layout__item 2/3 palm-1/1">
            <?php echo Form::label('title', _('Title')) ?>
            <?php
            echo Form::text(
                'title',
                $article->sections['content']->title,
                array('class' => 'text-input 1/1', 'id' => 'title')
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo Form::label('status', _('Status')) ?>
            <?php
            echo Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                $article->status,
                array('id' => 'status')
            ) ?>
        </div>
        <?php
        if (in_array('headline', $required_sections) || in_array('headline', $optional_sections)) { ?>
            <div class="layout__item 1/1 pt">
                <?php echo Form::label('required-section-headline', _('Headline')) ?>
                <?php
                echo Form::textarea(
                    'required-section-headline',
                    isset($article->sections['headline']) ? $article->sections['headline']->attributes['content'] : '',
                    array(
                        'rows'                     => '5',
                        'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                        'id'                       => 'required-section-headline',
                        'placeholder'              => _('Insert headline here ...')
                    )
                ) ?>
            </div>
        <?php
        } ?>
        <div class="layout__item 1/1 pt">
            <?php echo Form::label('required-section-content', _('Content')) ?>
            <?php
            echo Form::textarea(
                'required-section-content',
                isset($article->sections['content']) ? $article->sections['content']->attributes['content'] : '',
                array(
                    'data-redactor-min-height' => '330',
                    'rows'                     => '15',
                    'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                       => 'required-section-content',
                    'placeholder'              => _('Insert article content here ...')
                )
            ) ?>
        </div>
        <?php require_once __DIR__ . '/_partials/edit_extra_sections.php' ?>
        <?php require_once __DIR__ . '/_partials/edit_optional_sections.php' ?>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
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
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv-">
                <div class="media media--rev">
                    <div class="media__img">
                        <?php
                        if (has_access('manage_delete_admin_news_index_delete')) { ?>
                            <?php
                            echo Form::open(
                                array(
                                    'action' => action(
                                        '\Project\Controllers\Admin\News\Index@delete',
                                        array($article->id)
                                    ),
                                    'method' => 'delete',
                                    'class'  => 'filter'
                                )
                            ) ?>
                            <button class="btn btn--negative confirm i-trash-o" name="action" value="trash"
                                    type="submit"> <?php echo _('Delete') ?></button>
                            <?php echo Form::close() ?>
                        <?php
                        } ?>
                    </div>
                    <div class="media__body">
                        <?php echo HTML::link(action('\Project\Controllers\Admin\News\Index'), _('News')) ?>
                        /
                        <?php echo ellipsize(h($article->sections['content']->title), 50) ?>
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
