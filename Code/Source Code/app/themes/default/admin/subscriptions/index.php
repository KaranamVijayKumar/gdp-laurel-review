<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\Form;
use Story\HTML;

ob_start();
if (!count($items)) { ?>
    <div id="results">
        <p class="mb-"><em><?php echo _('There are no subscriptions.') ?></em></p>
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
            <div class="flag__img  1/2 lap-1/1 palm-1/1 mb0">
                <ul class="nav mv-- actions">
                    <li>
                        <?php
                        echo HTML::link(
                            action('\Project\Controllers\Admin\Subscriptions\Create'),
                            _('New'),
                            array('class' => 'i-edit 1/1', 'title' => _('New subscription'))
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
                                'data-base' => action('\Project\Controllers\Admin\Subscriptions\Index')
                            )
                        ) ?>
                    </li>
                    <li class="gray"><?php echo _('and') ?></li>
                    <li class="1/2 lap-3/4 palm-1/2">
                        <?php
                        echo Form::select(
                            'expire',
                            $expirations,
                            $selectedExpiration,
                            array(
                                'class'     => '1/1 actions__select',
                                'data-base' => action('\Project\Controllers\Admin\Subscriptions\Index')
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
                       data-url="<?php echo to(action('\Project\Controllers\Admin\Subscriptions\Index')) ?>"
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

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
