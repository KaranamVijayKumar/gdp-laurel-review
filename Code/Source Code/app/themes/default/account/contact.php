<?php
/*!
 * contact.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\Form;
use Story\HTML;

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
echo Form::open(array('errors'=>$errors)) ?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-gift icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><?php echo $title ?></h2>
        <p class="note u-mt0">
            Update your contact information. The following address will
            be used also as the <strong>shipping address</strong>.

        </p>
    </div>
</div>
<hr/>

<?php require __DIR__ . '/../_global/notifications.php'; ?>
<div class="text--user">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<div class="layout">

    <?php include __DIR__ . '/contact_fields.partial.php'; ?>
    <div class="cf"></div>
    <div class="layout__item u-1-of-5 u-1-of-1-palm u-pt u-mb">
        <?php echo Form::button(_('Save'), array('class' => 'btn 1/1', 'type'=>'submit')) ?>
    </div>
</div>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
<?php
echo Form::close();
?>
<hr/>
<p>
    <?php echo HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Back to Account')) ?>

</p>
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
    \Story\HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    \Story\HTML::link(action('\Project\Controllers\Account\Contact'), _('Address')),
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
