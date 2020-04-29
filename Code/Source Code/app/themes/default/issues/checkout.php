<?php
/*!
 * checkout.php v0.1
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
<h1 class="mt0 text--alert"><?php echo $title ?></h1>

<?php require __DIR__ . '/../_global/notifications.php'; ?>

<p>
    Once the payment was completed you should receive a confirmation email regarding your order.
</p>
<?php echo $checkoutForm; ?>
<hr/>
<p>
    <?php
    echo \Story\HTML::link(
        action('\Project\Controllers\Issues\Index'),
        sprintf(_('Back to issues'))
    ) ?>
</p>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
// global content aside

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
    \Story\HTML::link(action('\Project\Controllers\Issues\Index'), _('Issues')),
    $title
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
