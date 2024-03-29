<?php
/*!
 * contents.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\HTML;

ob_start();
?>
<?php if (count($issue->toc)) { ?>
    <ul class="nav mb">
        <?php
            foreach ($issue->toc as $item) { ?>

            <?php
                    if ($item->is_header) { ?>
                        <h3 class="mt-- mb0"><?php echo $item->content ?></h3>
            <?php } else { ?>
                <?php include __DIR__ . '/partials/toc_content_readonly.php' ?>
            <?php } ?>
        <?php
            } ?>
    </ul>
<?php } else { ?>
        <p class="orange mv">
            <span class="i--negative i-exclamation-circle"></span>
            <?php echo _('Table of Contents is empty. Please create it first.') ?>
        </p>
    <?php
        if (has_access('admin_issues_toc')) { ?>
            <p class="mv">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Toc', array($issue->id)),
                    _('Table of Contents')
                ) ?>
            </p>
    <?php
        } ?>

    <?php
} ?>
<div class="pb-">
    <?php
    echo HTML::link(
        action('\Project\Controllers\Admin\Issues\Show', array($issue->id)),
        ' ' . sprintf(_('Back to <q>%s</q>'), $issue->title),
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
            <?php echo _('Content') ?>
        </div>
    </div>
</div>
<?php

$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
// extra head

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
// extra footer

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
