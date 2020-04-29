<?php
/*!
 * create.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\Form;

ob_start();
?>

    <ul class="tabs-content">
        <li id="tab1" class="mv">
            <?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'overview.partial.php' ?>
        </li>
        <li id="tab2" class="mv">
            <?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'edit.partial.php' ?>
        </li>
        <li id="tab3" class="">
            <?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'history.partial.php' ?>
        </li>
    </ul>

<?php


$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--rev flag--editable">
            <div class="flag__img">
                <span class="btn btn--disabled nowrap">
                    <span class="black"><?php echo _($order->order_status) ?></span>
                </span>
            </div>
            <div class="flag__body gamma pv--">
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Orders\Index'),
                    _('Orders')
                ) ?>
                /
                <?php echo $order->orderId() ?>
            </div>
        </div>

        <div class="flag flag--rev flag--small flag--responsive ">

            <div class="flag__body">
                <ul class="tabs mv-">
                    <li class="tabs__item">
                        <a href="#tab1" class="tabs__link"><?php echo _('Overview') ?></a>
                    </li>
                    <li class="tabs__item">
                        <a href="#tab2" class="tabs__link"><?php echo _('Edit') ?></a>
                    </li>
                    <li class="tabs__item">
                        <a href="#tab3" class="tabs__link"><?php echo _('History') ?></a>
                    </li>
                </ul>
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
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/redactor.css') ?>">
<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <?php echo ws_redactor_assets('file', 'image', 'snippet', 'link'); ?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
