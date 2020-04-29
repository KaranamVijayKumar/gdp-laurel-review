<?php
/*!
 * create.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

require_once __DIR__ . '/../issues/sections.php';

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
?>
    <div class="text--user">
        <?php echo $engine->getSection('page-content') ?>
    </div>
<?php

require __DIR__ . '/../_global/notifications.php';

if (count($out_of_stock_items)) { ?>

    <div class="notifications">
        <ol>
            <li class="notification notification--negative">
                <div class="notification__img icon-sad"></div>
                <div class="notification__body">
                    <h6 class="notification__header">
                        <?php echo _('The following items are out of stock and were removed from your cart:') ?>
                    </h6>
                    <ul>
                    <?php
                        foreach ($out_of_stock_items as $item) { ?>

                            <li><?php echo $item->getName() ?></li>

                        <?php
                        } ?>
                    </ul>
                </div>
            </li>
        </ol>
    </div>

<?php

}

require __DIR__ . '/items.partial.php';

echo Story\Form::open(
    array(
        'errors'      => $errors,
        'id'          => 'paypal-checkout',
        'data-return' => action('\Project\Controllers\Cart\Index'),
        'data-submit' => action('\Project\Controllers\Cart\Checkout')
    )
);

if ($is_shippable) {
    require_once __DIR__ . '/shipping.partial.php';
}

if (count($cart->all())) {
    require_once __DIR__ . '/payment.partial.php';
}


// if one or more items are shippable we include the address fields



echo Story\Form::close();
?>
    <div class="text--user">
        <?php echo $engine->getSection('page-footer') ?>
    </div>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
    <div class="text--user">
        <?php echo $engine->getSection('page-aside') ?>
    </div>

    <?php
    if (!\Story\Auth::check()) { ?>
        <p class="note text--center"><?php echo _('To restore previous cart items please sign in.') ?></p>
        <p class="text--center">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Auth'),
                _('Sign In'),
                array('class' => 'btn u-1-of-1')
            ) ?>
        </p>
        <hr/>
    <?php
    } ?>
<?php

$global_content_aside = ob_get_clean();

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
// $breadcrumbs
// --------------------------------------------------------------
$breadcrumbs = array(
    \Story\HTML::link('', _('Home')),
    \Story\HTML::link(\Story\URL::current(), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
