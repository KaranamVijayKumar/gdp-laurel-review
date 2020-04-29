<?php
/*!
 * index.php v0.1
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
?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-upload icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><?php echo _('Your existing submissions') ?></h2>
        <p class="note u-mt0">
            View or manage your submissions.
        </p>
    </div>
</div>
<hr/>
<div class="text--user u-mb">
    <?php echo $engine->getSection('page-content'); ?>
</div>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<div class="u-1-of-1 instant-search js-instant-search">
    <input type="text" class="instant-search__field js-instant-search__field text-input u-1-of-1"
           placeholder="<?php echo _('Search your submissions&hellip;') ?>"
           data-content="#results"
           data-url="<?php echo to(action('\Project\Controllers\Submissions\Index', array($selectedStatus, $selectedCategory))) ?>"
           value="<?php echo $query ?>">
    <i class="instant-search__spinner js-instant-search__spinner icon--spin icon-refresh visuallyhidden"></i>
</div>
<ul class="nav u-mt- actions u-1-of-1">
    <li class="spoken-form">
        <?php echo Form::label('status', _('Select')) ?>
        <?php
        echo Form::select(
            'status',
            $statuses,
            $selectedStatus,
            array(
                'id' => 'status',
                'class' => 'js-actions__select u-1-of-4-palm',
                'data-base' => action('\Project\Controllers\Submissions\Index')
            )
        ) ?>

        <?php echo Form::label('category', _('in')) ?>
        <?php
        echo Form::select(
            'category',
            $categories,
            $selectedCategory,
            array(
                'class' => 'js-actions__select u-1-of-4-palm',
                'data-base' => action('\Project\Controllers\Submissions\Index')
            )
        ) ?>

        <?php
        echo HTML::link(
            action('\Project\Controllers\Submissions\Index'),
            '<small>' . _('Reset filters') .'</small>',
            array('class'=> '')
        ) ?>
    </li>
</ul>
<?php
if (!count($items)) { ?>
    <div class="notifications">
        <ol>
            <li class="notification">
                <div class="notification__img icon-sad"></div>
                <div class="notification__body">
                    <?php echo _('There are no submissions.') ?>
                </div>

            </li>
        </ol>
    </div>

    <?php
    } else {
        require __DIR__ . '/items.partials.php';
    }
?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer'); ?>
</div>
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
include "create_promo.partial.php";

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
    HTML::link(action('\Project\Controllers\Submissions\Index'), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
