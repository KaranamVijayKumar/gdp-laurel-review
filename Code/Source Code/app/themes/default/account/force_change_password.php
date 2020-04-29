<?php
/*!
 * force_change_password.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
// global content
?>
<div class="notifications">
    <ol>
        <li class="notification">
            <div class="notification__img icon-caution"></div>
            <div class="notification__body">
                <?php echo _('You account was locked for protection.
                In order to gain access to it, please change your password.') ?>
            </div>

        </li>
    </ol>
</div>
<?php
echo \Story\Form::open(array('errors'=>$errors));

require __DIR__ . '/../_global/notifications.php'; ?>

<p class="pt-">
    <?php echo \Story\Form::label('new_password', _('New Password')) ?>

    <?php
    echo \Story\Form::password(
        'new_password',
        array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'new', 'placeholder' => _('Min. 8 characters'))
    ) ?>

    <?php echo \Story\Form::label('verify_new_password', _('Verify New Password')) ?>

    <?php
    echo \Story\Form::password(
        'verify_new_password',
        array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'verify')
    ) ?>
</p>
<p class="">
    <?php echo \Story\Form::button(_('Change Password'), array('class' => 'btn u-1-of-1-palm', 'type'=>'submit')) ?>
</p>
<?php echo \Story\Form::close();

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
    \Story\HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    \Story\HTML::link(action('\Project\Controllers\Account\ChangePassword'), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
