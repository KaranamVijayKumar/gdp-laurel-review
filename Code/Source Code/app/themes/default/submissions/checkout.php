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
    Once the payment was completed you will be able to view your newly uploaded submission in your account page under
    <?php echo HTML::link(action('\Project\Controllers\Submissions\Index'), _('My Submissions')) ?>.
</p>
<?php echo $checkoutForm ?>

<p>
    <?php echo HTML::link(action('\Project\Controllers\Submissions\Index'), _('Back to My Submissions')) ?>

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
    HTML::link(action('\Project\Controllers\Submissions\Index'), _('My Submissions')),
    HTML::link(action('\Project\Controllers\Submissions\Create'), html2text($title))
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
