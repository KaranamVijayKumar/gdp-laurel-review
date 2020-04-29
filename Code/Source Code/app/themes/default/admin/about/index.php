<?php
/*!
 * index.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\HTML;

$ok = '<span class="green">✔</span>';
// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
    <h4 class="content-hero"><?php echo _('Log files') ?></h4>
    <p>
        <?php echo HTML::link(action('\Project\Controllers\Admin\Logs'), _('View the log files')) ?>
    </p>
    <h4 class="content-hero"><?php echo _('System check') ?></h4>
    <table class="1/1 table--striped">
        <tbody>
        <tr>
            <td class=""><?php echo _('PHP Version') ?></td>
            <td><?php echo version_compare(PHP_VERSION, '5.3.7', '>=') ? $ok : _('Upgrade PHP!') ?></td>
        </tr>
        <tr>
            <td><?php echo _('Storage folder') ?></td>
            <td><?php
                echo directory_is_writable(SP . 'storage') ? $ok : sprintf(
                    _('<strong class="error">%s</strong> is not writable!'),
                    SP . 'storage'
                ) ?>
            </td>
        </tr>
        <tr>
            <td><?php echo _('Database folder') ?></td>
            <td><?php
                echo directory_is_writable(SP . 'storage/database') ? $ok : sprintf(
                    _('<strong class="error">%s</strong> is not writable!'),
                    SP . 'storage/database'
                ) ?></td>
        </tr>
        <tr>
            <td><?php echo _('Files folder') ?></td>
            <td><?php
                echo directory_is_writable(UPLOADS_PATH) ? $ok : sprintf(
                    _('<strong class="error">%s</strong> is not writable!'),
                    UPLOADS_PATH
                ) ?></td>
        </tr>
        <tr>
            <td><?php echo _('Logs folder') ?></td>
            <td><?php
                echo directory_is_writable(SP . 'storage/log') ? $ok : sprintf(
                    _('<strong class="error">%s</strong> is not writable!'),
                    SP . 'storage/log'
                ) ?></td>
        </tr>
        <tr>
            <td><?php echo _('Uploads folder') ?></td>
            <td><?php
                echo directory_is_writable(PP . 'uploads') ? $ok : sprintf(
                    _('<strong class="error">%s</strong> is not writable!'),
                    PP . 'uploads'
                ) ?></td>
        </tr>
        <tr>
            <td><?php echo _('Thumbnails folder') ?></td>
            <td><?php
                echo directory_is_writable(PP . 'uploads/thumbnails') ? $ok : sprintf(
                    _('<strong class="error">%s</strong> is not writable!'),
                    PP . 'uploads/thumbnails'
                ) ?></td>
        </tr>
        </tbody>
    </table>
    <?php
    foreach ($list as $key => $item) {
        if ($key == '__self') {
            include __DIR__ . '/self.php';
        } else {
            include __DIR__ . '/info.php';
        }
    } ?>
    <div></div>
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
                <?php echo $title ?>
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
