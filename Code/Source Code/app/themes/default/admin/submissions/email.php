<?php
/*!
 * email.php v0.1
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
<h4 class="mb0">
    <?php echo _('Send email to author') ?>
</h4>
<p class="gray"><?php echo _('You are about to send an email to the author of this submission:') ?></p>
<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">
        <div class="layout__item">
            <?php echo Form::label('subject', _('Subject')) ?>
            <?php
            echo Form::text(
                'subject',
                sprintf(_('%s Submission: %s'), $site_title, ellipsize($submission->name, 200, .5, '...')),
                array('class' => 'text-input 1/1', 'id' => 'name')
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo Form::label('message', _('Message')) ?>
            <?php
            echo Form::textarea(
                'message',
                '',
                array(
                    'rows'                     => '10',
                    'class'                    => 'text-input 1/1 text-input--redactor',
                    'id'                       => 'coverletter',
                    'placeholder'              => _('Insert message here ...')
                )
            ) ?>

        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php
            echo Form::button(
                _('Send'),
                array('class' => 'btn 1/1 btn--positive confirm', 'type' => 'submit')
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
$hide_tabs = true;
require_once __DIR__ .'/partials/toolbar.partial.php';

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
<script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
