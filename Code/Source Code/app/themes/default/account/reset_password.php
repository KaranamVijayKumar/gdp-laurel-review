<?php
// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
// global content
echo Story\Form::open(array('errors' => $errors)) ?>
<p>
    Please provide the email address which is associated with the Laurel Review account.
</p>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<p>
    <?php echo Story\Form::label('email', _('E-mail address')) ?>

    <?php
    echo Story\Form::email(
        'email',
        '',
        array('class' => 'text-input u-2-of-3 u-1-of-1-palm u-mb', 'id' => 'email')
    ) ?>
</p>
<p  class="pt">
    <?php echo \Story\Form::label('new_password', _('New Password')) ?>

    <?php
    echo \Story\Form::password('new_password', array('class' => 'text-input  u-2-of-3 u-1-of-1-palm', 'id' => 'new')) ?>

    <?php echo \Story\Form::label('verify_new_password', _('Verify New Password')) ?>

    <?php
    echo \Story\Form::password(
        'verify_new_password',
        array('class' => 'text-input  u-2-of-3 u-1-of-1-palm', 'id' => 'verify')
    ) ?>
</p>
<p>

    <?php echo Story\Form::button(_('Change Password'), array('class' => 'btn u-1-of-1-palm', 'type'=>'submit')) ?>
</p>
<p>
    <?php echo \Story\HTML::link(action('\Project\Controllers\Auth'), _('Sign In')) ?>
</p>
<?php echo Story\Form::close();
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
    \Story\HTML::link(action('\Project\Controllers\Account\ResetPassword', array($token)), _('Reset Password'))
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
