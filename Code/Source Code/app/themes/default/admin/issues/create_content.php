<?php
/*!
 * create_content.php v0.1
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
    <h4 class=" content-hero"><?php echo _('Info') ?></h4>
    <table class="1/1 mv">
        <thead>
        <tr>
            <th class="1/4 palm-1/3"><?php echo _('Author') ?></th>
            <th><?php echo _('Title') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $toc->content ?></td>
            <td><?php echo $toc_title->content ?></td>
        </tr>
        </tbody>
    </table>
    <h4 class="content-hero"><?php echo _('Properties') ?></h4>
    <div class="layout ph-- mb">

        <div class="layout__item mb 1/2 palm-1/1">
            <?php echo Form::label('status', _('Status')) ?>

            <?php
            echo Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                1,
                array('id' => 'status')
            ) ?>

        </div><!--
     --><div class="layout__item mb 1/2 palm-1/1">
            <?php echo Form::label('highlight', _('Include in the issue highlights')) ?>

            <?php
            echo Form::select(
                'highlight',
                array('1' => _('Yes'), '0' => _('No')),
                0,
                array('id' => 'highlight')
            ) ?>

        </div>
        <div class="layout__item ">
            <?php echo Form::label('content', _('Content')) ?>
            <?php
            echo Form::textarea(
                'content',
                '',
                array(
                    'rows'                     => '10',
                    'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                       => 'coverletter',
                    'placeholder'              => _('Insert content here ...')
                )
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt--">
            <?php
            echo Form::button(
                _('Create content'),
                array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
            ) ?>
        </div>
    </div>
    <div class="pb-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Issues\Content', array($issue->id)),
            ' ' . sprintf(_('Back to <q>%s</q> content'), $issue->title),
            array('class' => 'i-angle-double-left')
        ) ?>
    </div>
<?php
echo Form::close();
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive flag--rev">
            <div class="flag__body gamma pv--">
                <div class="media media--rev media--responsive">
                    <?php
                    echo HTML::link(
                        action('\Project\Controllers\Admin\Issues\Index'),
                        _('Issues')
                    ) ?>
                    /
                    <?php
                    echo HTML::link(
                        action('\Project\Controllers\Admin\Issues\Show', array($issue->id)),
                        h($issue->title)
                    ) ?>
                    /
                    <?php
                    echo HTML::link(
                        action('\Project\Controllers\Admin\Issues\Content', array($issue->id)),
                        _('Content')
                    ) ?>
                    /
                    <?php echo acronym($toc->content) . ': ' . $toc_title->content ?>
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
