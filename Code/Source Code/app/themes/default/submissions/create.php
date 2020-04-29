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
// Title
// --------------------------------------------------------------
if (!isset($title)) {
    $title = h($main_page_content->title);
}

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-upload icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0 text--alert"><?php echo $title ?></h2>
        <p class="note u-mt0">
            Create a new submission.
        </p>
    </div>
</div>
<hr/>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<?php
echo Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data', 'id' => 'paypal-payment')) ?>

<div class="u-mb-">
    <?php echo Form::label('category', _('Category')) ?>

    <?php
    echo Form::select(
        'category',
        $categories,
        '0',
        array('class' =>'js-submission-details-selector u-1-of-1-palm')
    ) ?>
</div>

<div class="js-submission-details">
    <div class="js-submission--0 u-mb-">
        <em class="note"><?php echo _('Please select a category to view the submission guidelines.') ?></em>
    </div>
    <?php
    foreach ($categoryCollection as $category) { ?>

        <div class="submission submission--<?php
        echo $category->slug ?> js-submission--<?php echo $category->id ?> visuallyhidden">
            <h3 class="submission__title u-mv0">
                <strong>
                    <?php echo h($category->name) ?> - <?php echo money_format('%n', $category->amount) ?>
                </strong>
            </h3>
            <?php
                if ($category->guidelines) { ?>
                    <div class="submission__guidelines">
                        <?php echo $category->guidelines ?>
                    </div>
            <?php
                } ?>

            <?php
                if ($category->size_limit) { ?>
                    <div>
                        <span class="label"><?php echo _('Size limit') ?></span>
                        <?php echo $category->size_limit ?>
                    </div>
            <?php
                } ?>
        </div>
    <?php
    } ?>
</div>
<p>
    <?php echo Form::label('name', _('Name')) ?>
    <?php echo Form::text('name', '', array('class' => 'text-input u-2-of-3 u-1-of-1-palm')) ?>
</p>
<p>
    <?php echo Form::label('coverletter', _('Cover letter')) ?>

    <?php
    echo Form::textarea(
        'coverletter',
        '',
        array(
            'rows'                     => '10',
            'class'                    => 'text-input text-input--redactor',
            'id'                       => 'coverletter',
            'placeholder'              => _('Insert cover letter content here ...')
        )
    ) ?>

    <?php echo Form::label('file', _('File')) ?>
    <?php echo Form::file('file') ?>
    <br/>
    <span class="note u-mt0">
        <?php
        printf(
            _('You can upload %1$s files with the maximum size of %2$s.'),
            '<strong>' . implode(', ', \Project\Models\Submission::$fileTypes) . '</strong>',
            get_file_size(max_upload_size())
        ); ?>
    </span>
</p>
<p>
    <?php
    echo Form::button(
        _('Add to Cart'),
        array('type' => 'submit', 'class' => 'btn btn--alert')
    ) ?>
</p>
<?php echo Form::close() ?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
    <hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Submissions\Index'), _('Back to My Submissions')) ?>

</p>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
<div class="text--user">
    <?php echo $engine->getSection('page-aside'); ?>
</div>
<?php
include 'category_prices.partial.php';

$global_content_aside = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
?>
<!-- redactor style -->
<link rel="stylesheet" href="<?php echo to('themes/default/redactor/redactor.css') ?>">
<?php

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
<!-- redactor scripts -->
<script type="text/javascript" src="<?php echo to('themes/default/vendor/redactor/redactor.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo to('themes/default/redactor/redactor.min.js') ?>" charset="utf-8"></script>
<!-- /end redactor scripts -->
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// $breadcrumbs
// --------------------------------------------------------------
$breadcrumbs = array(
    HTML::link('', _('Home')),
    HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    HTML::link(action('\Project\Controllers\Submissions\Index'), _('My Submissions')),
    HTML::link(action('\Project\Controllers\Submissions\Create'), html2text($title))
);
// --------------------------------------------------------------
// Overrides
// --------------------------------------------------------------
$palm_hidden = 1;

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
