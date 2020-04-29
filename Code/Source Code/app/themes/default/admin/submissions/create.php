<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data')) ?>
    <h4 class="content-hero"><?php echo _('Name and Cover Letter') ?></h4>
    <div class="layout ph-- mb">
        <div class="layout__item ">
            <?php echo \Story\Form::label('name', _('Name')) ?>
            <?php
            echo \Story\Form::text(
                'name',
                '',
                array('class' => 'text-input 1/1', 'id' => 'name')
            ) ?>
        </div>
        <div class="layout__item pt-">
            <?php echo \Story\Form::label('coverletter', _('Cover letter') . ' (' . _('optional') . ')') ?>
            <?php
            echo \Story\Form::textarea(
                'coverletter',
                '',
                array(
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
                    'placeholder'                       => _('Insert cover letter content here ...')
                )
            ) ?>
        </div>
    </div>
    <h4 class="content-hero"><?php echo _('Author') ?></h4>
    <div class="layout ph-- mb">
        <div class="layout__item">
            <?php echo \Story\Form::label('user', _('Assign to user')) ?>
            <?php
            echo \Story\Form::select(
                'user',
                array('' => '') + $users,
                '',
                array(
                    'id' => 'user',
                    'class' => 'chosen-select  1/3 lap-1/1 palm-1/1',
                    'data-placeholder' => _('Select a user...')
                )
            ) ?>
        </div>
    </div>
    <h4 class="content-hero"><?php echo _('Properties') ?></h4>
    <div class="layout ph-- mb">
        <div class="layout__item">
            <?php echo \Story\Form::label('status', _('Status')) ?>
            <?php echo \Story\Form::select('status', $statuses, $status, array('id' => 'status')) ?>
        </div>
        <div class="layout__item pt">
            <?php echo \Story\Form::label('category', _('Category')) ?>
            <?php echo \Story\Form::select('category', $categories, $category, array('id' => 'category')) ?>
        </div>
    </div>
    <h4 class="content-hero"><?php echo _('File') ?></h4>
    <div class="layout ph--">
        <div class="layout__item ">
            <?php echo \Story\Form::label('file', _('File')) ?>
            <?php echo \Story\Form::file('file', array('id' => 'file')) ?>
            <p class="gray">
                <small>
                    <?php
                    printf(
                        _('You can upload %1$s files with the maximum size of %2$s.'),
                        '<strong>' . implode(', ', \Project\Models\Submission::$fileTypes) . '</strong>',
                        get_file_size(max_upload_size())
                    ); ?>
                </small>
            </p>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pb">
            <?php
            echo \Story\Form::button(
                _('Create'),
                array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
            ) ?>
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
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Submissions\Index'),
                    _('Submissions')
                ) ?>
                /
                <?php echo _('New Submission') ?>
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
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">
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
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
