<?php
/*!
 * page.php v0.1
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
// global content
require __DIR__ . '/../_global/notifications.php';
?>
<div class="text--user">
<?php echo $engine->getSection('page-content'); ?>
</div>

<h2 class="u-mv0"><?php echo _('Subscribe') ?></h2>
<?php require_once __DIR__ . '/subscribe.form.partial.php' ?>

<p>
    <?php echo _('In order to unsubscribe please use the following link:') ?><br/>
    <?php echo \Story\HTML::link(action('\Project\Controllers\Newsletter\Unsubscribe'), _('Unsubscribe from our newsletter')) ?>
</p>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
// global content aside
echo $engine->getSection('page-aside');
echo $engine->getSection('aside-last-issue');

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
// Overrides
// --------------------------------------------------------------
$palm_hidden = 1;

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
