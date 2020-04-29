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

$findPage = array('1','15');
if (!in_array($this->main_page_content->attributes['page_id'],$findPage)) {
    echo $engine->getSection('page-aside');
    echo $engine->getSection('aside-last-issue');

}
//print_r($this->main_page_content->attributes['name']);
//exit;

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
    \Story\HTML::link('/' . $page->slug, $title)
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
