<?php

use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<h4 class=""><?php echo _('Biography') ?></h4>
<p><?php echo _('Make sure your biography is up-to-date.')?></p>

<?php echo \Story\Form::open(array('errors'=>$errors)) ?>
<div class="layout ph-- mb">
    <div class="layout__item ">
        <?php echo \Story\Form::label('content', _('Short biography')) ?>
        <?php
        echo \Story\Form::textarea(
            'content',
            $biography->content,
            array(
                // must have the same redactor view like on the client side
                'data-redactor-min_height'          => '330',
                'data-redactor-buttons'             => '["bold", "italic", "deleted","outdent", "indent","link"]',
                'data-redactor-allowed-tags'        => '["p", "strong", "em", "del", "a"]',
                'data-redactor-plugins'             => '[]',
                'data-redactor-toolbar-fixed'       => 'false',
                'data-redactor-drag-image-upload'   => 'false',
                'data-redactor-drag-file-upload'    => 'false',
                'data-redactor-remove-comments'     => 'true',
                'data-redactor-remove-data-attr'    => 'true',
                'rows'                              => '10',
                'class'                             => 'text-input 1/1 text-input--redactor',
                'id'                                => 'coverletter',
                'placeholder'                       => _('Insert biography here ...')
            )
        ) ?>

    </div>
    <div class="cf"></div>
    <div class="layout__item 1/5 palm-1/1">
        <?php echo \Story\Form::button(_('Save'), array('class' => 'btn 1/1', 'type'=>'submit')) ?>
    </div>
</div>
<?php
echo \Story\Form::close();
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
<div class="flag__body">
    <div class="flag flag--small flag--rev">

        <div class="flag__img">
            <?php echo HTML::gravatar($user->email, 32, '', 'mm'); ?>
        </div>
        <div class="flag__body gamma pv--">
            <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Account\Dashboard'), $title) ?>
            /
            <?php echo _('Biography') ?>
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

<script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>

<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
