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
<?php echo Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data')) ?>
    <div class="layout ph-- mb">
        <div class="layout__item  2/3 palm-1/1 mt">
            <?php echo Form::label('title', _('Title')) ?>
            <?php
            echo Form::text(
                'title',
                $chapbook->title,
                array('class' => 'text-input 1/1', 'id' => 'title')
            ) ?>
            <p class="gray mb0">
                <em><?php echo _('Chaning the title will also change the links to the chapbook on the site.') ?></em>
            </p>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1 mt">
            <?php echo Form::label('status', _('Status')) ?>
            <?php
            echo Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                (int)$chapbook->status,
                array('id' => 'status')
            ) ?>
        </div>
        <div class="layout__item pt- mt-">
            <?php echo Form::label('short_description', _('Short description')) ?>
            <?php
            echo Form::textarea(
                'short_description',
                $chapbook->contents->findBy(
                    'name',
                    'short_description',
                    $chapbook->default_content
                )->attributes['content'],
                array(
                    'data-redactor-min_height' => '110',
                    'rows'                     => '10',
                    'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                       => 'short_description',
                    'placeholder'              => _('Insert short description here ...'),
                )
            ) ?>
        </div>
        <div class="layout__item 1/2 palm-1/1">
            <h4 class="content-hero"><?php echo _('Inventory') ?></h4>
            <?php echo \Story\Form::label('inventory', _('Chapbooks in stock')) ?>
            <?php
            echo \Story\Form::number(
                'inventory',
                $chapbook->inventory,
                array(
                    'min'   => '0',
                    'max'   => '1000000000',
                    'step'  => '1',
                    'class' => 'text-input text-input--small',
                    'id'    => 'amount'
                )
            ) ?>
            <p class="gray">
                <small>
                    <?php
                    echo _('Inventory is required when the chapbook is enabled.')
                    ?>
                    <br/>
                    <?php
                    echo _('You can set to <q>0</q> is no chapbooks are available to buy.')
                    ?>
                    <br/>
                    <?php
                    echo _('This number also reflects the current stock, since it decreases as chapbooks are bought.')
                    ?>
                </small>
            </p>
        </div><!--
     --><div class="layout__item 1/2 palm-1/1">
            <h4 class="content-hero"><?php echo _('Cover page image') ?></h4>
            <div class="media media--responsive">
                <?php
                if ($chapbook->cover_image) { ?>
                    <div class="media__img 1/8 lap-1/5 palm-1/1">
                        <a href="<?php echo $chapbook->cover_image->getCoverPageImageUrl() ?>">
                            <img src="<?php echo $chapbook->cover_image->getCoverPageImageUrl() ?>"
                                 alt="<?php echo _('Cover page image') ?>" class=" generic-img palm-1/1"/>
                        </a>
                    </div>
                <?php
                } ?>
                <div class="media__body">
                    <?php echo Form::label('file', _('File')) ?>
                    <?php echo Form::file('file', array('id' => 'file')) ?>
                    <p class="gray">
                        <small>
                            <?php
                            printf(
                                _('You can upload %1$s files with the maximum size of %2$s.') . '<br>' .
                                _(
                                    'For best results make sure to upload only the cover image in portrait orientation.'
                                ) . '<br>' .
                                _('The uploaded image will be resized automatically for better visitor experience.'),
                                '<strong>' . implode(
                                    ', ',
                                    \Project\Models\ChapbookFile::$coverPageFileTypes
                                ) . '</strong>',
                                get_file_size(max_upload_size())
                            ); ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
        <?php require_once __DIR__ . '/partials/edit_optional_sections.php' ?>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php
            echo Form::button(
                _('Save'),
                array('class' => 'btn 1/1', 'type' => 'submit')
            ) ?>
        </div>
    </div>
<?php echo Form::close() ?>
    <div class="pb-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Chapbooks\Show', array($chapbook->id)),
            ' ' . sprintf(_('Back to <q>%s</q>'), $chapbook->title),
            array('class' => 'i-angle-double-left')
        ) ?>
    </div>
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
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Chapbooks\Index'),
                    _('Chapbooks')
                ) ?>
                /
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Chapbooks\Show', array($chapbook->id)),
                    h($chapbook->title)
                ) ?>
                /
                <?php echo _('Properties') ?>
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
