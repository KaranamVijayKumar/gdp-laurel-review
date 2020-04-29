<?php
/*!
 * 404.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

// --------------------------------------------------------------
// Title
// --------------------------------------------------------------
if (!isset($title)) {
    $title = _('Page Not Found');
}
// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<h1 class="u-mt0"><?php echo $title ?></h1>
<div class="notifications">
    <ol>
        <li class="notification notification--negative">
            <div class="notification__img icon-caution"></div>
            <div class="notification__body">
                <?php echo _('Sorry, we could not find the page you were looking for.') ?>
            </div>

        </li>
    </ol>
</div>
<?php
echo $engine->getSection('page-content');
echo $engine->getSection('page-footer');
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
echo $engine->getSection('page-side');

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
    $title
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';


