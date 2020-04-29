<?php
/*!
 * log.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<h4 class="content-hero"><?php echo $subtitle ?></h4>
<?php
if ($content) { ?>
    <small>
        <pre><?php echo $content ?></pre>
    </small>
<?php
} else { ?>
    <p>
        <em><?php echo _('This log file is empty.') ?></em>
    </p>
    <?php
}
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <?php echo HTML::link(action('\Project\Controllers\Admin\About'), _('About')) ?>
                /
                <?php echo HTML::link(action('\Project\Controllers\Admin\Logs'), $title) ?>
                /
                <?php echo $subtitle ?>
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
