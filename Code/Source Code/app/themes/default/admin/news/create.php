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
<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mv">
        <div class="layout__item 2/3 palm-1/1">
            <?php echo Form::label('title', _('Title')) ?>
            <?php
            echo Form::text(
                'title',
                '',
                array('class' => 'text-input 1/1', 'id' => 'title')
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo Form::label('status', _('Status')) ?>
            <?php
            echo Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                '1',
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
                    '',
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
                '',
                array(
                    'data-redactor-min-height' => '330',
                    'rows'                     => '15',
                    'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                       => 'required-section-content',
                    'placeholder'              => _('Insert article content here ...')
                )
            ) ?>

        </div>
        <?php require_once __DIR__ . '/_partials/create_extra_sections.php' ?>
        <?php require_once __DIR__ . '/_partials/create_optional_sections.php' ?>
        <div class="layout__item pt 1/1 check-list">
            <?php echo Form::checkbox('newsletter', 1, false, array('id' => 'newsletter')) ?>
            <?php
            echo Form::label(
                'newsletter',
                _(
                    'Send this news article as a newsletter as well'.
                    '(article must be enabled and only the content and the headline will be sent).'
                )
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php echo Form::button(_('Create'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
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
                <?php echo HTML::link(action('\Project\Controllers\Admin\News\Index'), _('News')) ?>
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
