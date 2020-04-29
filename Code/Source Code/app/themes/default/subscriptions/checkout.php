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
    Once the payment was completed you will be able to view your subscription in your account page under
    <?php echo HTML::link(action('\Project\Controllers\Subscriptions\Index'), _('Subscriptions')) ?>.
</p>
<?php echo $checkoutForm ?>
<hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Subscriptions\Cancel', array($subscription->id)), _('Cancel')) ?>

</p>
<hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Subscriptions\Index'), _('Back to Subscriptions')) ?>

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
    HTML::link('', _('Home')),
    HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    HTML::link(action('\Project\Controllers\Subscriptions\Index'), _('Subscriptions')),
    HTML::link(action('\Project\Controllers\Subscriptions\Checkout'), html2text($title))
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
