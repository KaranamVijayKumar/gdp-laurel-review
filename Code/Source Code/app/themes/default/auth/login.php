<?php
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
<?php echo Story\Form::open(array('errors' => $errors)) ?>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<?php echo Story\Form::label('email', _('E-mail address')) ?>

<?php echo Story\Form::email('email', '', array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'email')) ?>

<?php echo Story\Form::label('password', _('Password')) ?>

<?php echo Story\Form::password('password', array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'password')) ?>

<?php echo Story\Form::button(_('Sign In'), array('class' => 'btn u-1-of-4 u-1-of-1-palm u-mv-', 'type'=>'submit')) ?>

    <p>
        <?php echo \Story\HTML::link(action('\Project\Controllers\Account\Forgot'), _('Reset Password')) ?>

    </p>
<?php echo Story\Form::close();


if (has_access('account_create')) { ?>
    <hr/>
    <p><?php echo _('Create a free account.') ?></p>
    <p>
        <?php
        echo \Story\HTML::link(
            action('\Project\Controllers\Account\Create'),
            _('Create Account'),
            array('class' => 'btn u-1-of-1-palm btn--alert')
        ) ?>
    </p>
    <hr/>
    <?php
}
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
    \Story\HTML::link(action('\Project\Controllers\Auth'), _('Sign In'))
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
