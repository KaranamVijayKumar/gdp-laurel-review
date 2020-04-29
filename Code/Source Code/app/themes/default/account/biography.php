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
// Title
// --------------------------------------------------------------
if (!isset($title)) {
    $title = h($main_page_content->title);
}

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
echo Form::open(array('errors'=>$errors));
?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-briefcase icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><?php echo $title ?></h2>
        <p class="note u-mt0">
            <?php echo _('Make sure your biography is up-to-date.') ?>
        </p>
    </div>
</div>
<hr/>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>
<p>
    <?php echo Form::label('content', _('Short biography')) ?>

    <?php
    echo Form::textarea(
        'content',
        $biography->content,
        array(
            'rows'                     => '10',
            'class'                    => 'text-input text-input--redactor u-1-of-1',
            'id'                       => 'coverletter',
            'placeholder'              => _('Insert biography here ...')
        )
    ) ?>
</p>

<p class="">
    <?php echo Form::button(_('Save'), array('type' => 'submit', 'class' => 'btn')) ?>
</p>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
<hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Back to Account')) ?>

</p>
<?php
echo Form::close();

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
    HTML::link(action('\Project\Controllers\Account\Biography'), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
