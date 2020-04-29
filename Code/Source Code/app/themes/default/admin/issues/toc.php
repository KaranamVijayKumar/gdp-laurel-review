<?php
/*!
 * toc.php v0.1
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
<p>
    <?php
    echo _(
        "This is the issue's table of contents. To add a new author or heading select the buttons below." .
        "Empty fields or titles without an author will be <strong class='red'>removed</strong> when saving."
    ) ?>
</p>
<?php echo Form::open(array('errors' => $errors)) ?>
<div class="js-issues-toc">
    <ul class="js-issues-toc__list issues-toc__list nav">
        <?php
        foreach ($issue->toc as $item) { ?>
            <?php
            if ($item->is_header) { ?>
                <?php include __DIR__ .'/partials/toc_header.php' ?>
            <?php } else { ?>
                <?php include __DIR__ .'/partials/toc_content.php' ?>
            <?php } ?>
        <?php
        } ?>
    </ul>
    <?php include_once 'partials/toc_template.php' ?>
    <div class="1/5 palm-1/1 pt">
        <a href="#" class="js-issues-toc__addHeader i-plus btn btn--secondary">
            <?php echo _('Heading') ?>
        </a>
        <a href="#" class="js-issues-toc__addContent i-plus btn btn--secondary">
            <?php echo _('Author') ?>
        </a>
    </div>
    <div class="1/5 palm-1/1 pv">
        <?php
        echo Form::button(
            _('Save'),
            array('class' => 'btn 1/1', 'type' => 'submit')
        ) ?>
    </div>
    <div class="pb-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Issues\Show', array($issue->id)),
            ' ' . sprintf(_('Back to <q>%s</q>'), $issue->title),
            array('class' => 'i-angle-double-left')
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
?>
<div class="flag__body">
    <div class="flag flag--small flag--responsive ">
        <div class="flag__body gamma pv--">
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
            <?php echo _('Table of Contents') ?>
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
<link rel="stylesheet" href="<?php echo to('themes/default/admin/issues/issue-toc.css') ?>">
<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>

<script src="<?php echo to('themes/default/admin/issues/issuesTocManager.min.js') ?>"></script>
<script>$('.js-issues-toc').issuesTocManager().data();</script>

<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
