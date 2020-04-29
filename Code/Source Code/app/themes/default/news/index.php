<?php
/*!
 * index.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

include __DIR__ . '/sections.php';
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
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php

require __DIR__ . '/../_global/notifications.php';
?>

<h1 class="content-hero content-hero--secondary"><?php echo _('Latest News') ?></h1>
<?php
echo $engine->getSection('latest-news');
?>

<?php
echo $engine->getSection('older-news');
echo $engine->getSection('news-footer');
?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
<div class="text--user">
    <?php echo $engine->getSection('page-aside'); ?>
</div>
<?php

echo $engine->getSection('aside-last-issues');

echo $engine->getSection('news-side');

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
    \Story\HTML::link(action('\Project\Controllers\News\Index'), $title)
);

// --------------------------------------------------------------
// Overrides
// --------------------------------------------------------------
$palm_hidden = 1;

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ . '/../_masters/page.master.php';
