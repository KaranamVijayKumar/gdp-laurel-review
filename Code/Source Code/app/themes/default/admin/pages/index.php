<?php
/*!
 * categories.php v0.1
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
if (!count($items)) { ?>
    <div id="results">
        <p class="mb-"><em><?php echo _('There are no pages.') ?></em></p>
    </div>
<?php } else { ?>
    <?php require __DIR__ . '/items.partial.php' ?>
<?php } ?>
<?php


$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive ">
            <div class="flag__img mb0">
                <ul class="nav mv-- actions">
                    <li>
                        <?php
                        echo HTML::link(
                            action('\Project\Controllers\Admin\Pages\Create'),
                            _('New'),
                            array('class' => 'i-edit 1/1', 'title' => _('New page'))
                        ) ?>
                    </li>
                    <li class="action__separator"></li>
                </ul>
            </div>
            <div class="flag__body">
                <label class="action__search-label i-search" title="<?php
                echo _('Instant search&hellip;') ?>" for="instant-search"></label>
                <input type="text" value="<?php echo $query ?>" class="actions__search text-input"
                       placeholder="<?php echo _('Instant search&hellip;') ?>" data-content="#results"
                       data-url="<?php echo to(action('\Project\Controllers\Admin\Pages\Index')) ?>"
                       id="instant-search"/>
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
