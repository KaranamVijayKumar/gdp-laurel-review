<?php
/*!
 * biography.php v0.1
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
<h4 class=""><?php echo _('User biography') ?></h4>
<p><?php echo _("Make sure the user's biography is up-to-date.") ?></p>
<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">
        <div class="layout__item ">
            <?php echo Form::label('content', _('Short biography')) ?>
            <?php
            echo Form::textarea(
                'content',
                $biography->content,
                array(
                    'data-redactor-min_height'        => '330',
                    'data-redactor-buttons'           => '["bold", "italic", "deleted","outdent", "indent","link"]',
                    'data-redactor-allowed-tags'      => '["p", "strong", "em", "del", "a"]',
                    'data-redactor-plugins'           => '[]',
                    'data-redactor-toolbar-fixed'     => 'false',
                    'data-redactor-drag-image-upload' => 'false',
                    'data-redactor-drag-file-upload'  => 'false',
                    'data-redactor-remove-comments'   => 'true',
                    'data-redactor-remove-data-attr'  => 'true',
                    'rows'                            => '10',
                    'class'                           => 'text-input 1/1 text-input--redactor',
                    'id'                              => 'coverletter',
                    'placeholder'                     => _('Insert biography here ...')
                )
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt+">
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
$user_subtitle = _('Biography');
require_once __DIR__ . '/toolbar.partial.php';
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

include __DIR__ . '/../_masters/page.master.php';
