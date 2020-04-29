<?php
/*!
 * show.php v0.1
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
    <ul class="tabs-content">
        <?php
        if ($filePreview) { ?>
            <li id="tab1">
            <?php
                if ($filePreview instanceof \stdClass) { ?>
                    <div class="viewer" data-url="<?php echo $filePreview->urls->assets ?>"></div>
            <?php
                } else { ?>
                <?php echo $filePreview ?>
            <?php
                } ?>
            </li>
        <?php
        } ?>
        <li id="tab2">
            <?php include __DIR__ . '/partials/activity.php' ?>
        </li>
        <li id="tab3">
            <?php include __DIR__ . '/partials/properties.php' ?>
        </li>
        <?php
        if ($partial_withdrawn) { ?>
            <li id="tab4">
                <?php include __DIR__ . '/partials/withdrawals.php' ?>
            </li>
        <?php
        } ?>
    </ul>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
require_once __DIR__ . '/partials/toolbar.partial.php';

$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
?>
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/box/crocodoc.viewer.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/submissions/submissions.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/redactor.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/editor.css') ?>">

<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <script src="<?php echo to('themes/default/admin/box/crocodoc.viewer.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/submissions/submissions.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
