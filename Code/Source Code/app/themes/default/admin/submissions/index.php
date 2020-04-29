<?php
/*!
 * index.php v0.1
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
<?php if (!count($items)) { ?>
    <div id="results">
        <p class="mb-"><em><?php echo _('There are no submissions.') ?></em></p>
    </div>
<?php } else { ?>
    <?php require __DIR__ . '/partials/items.php' ?>
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
            <div class="flag__img mb0 1/2 lap-1/1 palm-1/1">
                <ul class="nav mv-- actions  1/1">
                    <li>
                        <?php
                        echo HTML::link(
                            action('\Project\Controllers\Admin\Submissions\Create'),
                            _('New'),
                            array('class' => 'i-edit 1/1', 'title' => _('New submission'))
                        ) ?>
                    </li>
                    <li class="action__separator"></li>
                    <li class="gray"><?php echo _('Select') ?></li>
                    <li class="1/2 lap-1/4 palm-1/2">
                        <?php
                        echo Form::select(
                            'status',
                            $statuses,
                            $selectedStatus,
                            array(
                                'class'     => '1/1 actions__select',
                                'data-base' => action('\Project\Controllers\Admin\Submissions\Index')
                            )
                        ) ?>
                    </li>
                    <li class="gray"><?php echo _('in') ?></li>
                    <li class="1/2 lap-3/4 palm-1/2">
                        <?php
                        echo Form::select(
                            'category',
                            $categories,
                            $selectedCategory,
                            array(
                                'class'     => '1/1 actions__select',
                                'data-base' => action('\Project\Controllers\Admin\Submissions\Index')
                            )
                        ) ?>
                    </li>
                </ul>
            </div>
            <div class="flag__body">
                <label class="action__search-label i-search" title="<?php
                echo _('Instant search&hellip;') ?>" for="instant-search"></label>
                <input type="text" value="<?php echo $query ?>" class="actions__search text-input"
                       placeholder="<?php echo _('Instant search&hellip;') ?>" data-content="#results"
                       id="instant-search"
                       data-url="<?php
                       echo to(
                           action(
                               '\Project\Controllers\Admin\Submissions\Index',
                               array($selectedStatus, $selectedCategory)
                           )
                       ) ?>"/>
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
?>
<script src="<?php echo to('themes/default/admin/submissions/submissions.min.js') ?>"></script>
<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
