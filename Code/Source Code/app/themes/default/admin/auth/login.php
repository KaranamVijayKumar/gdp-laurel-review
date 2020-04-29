<?php
/*!
 * login.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\Form;
use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
// global content
?>
<div class="content mb-">
    <?php echo Form::open() ?>


            <?php echo Form::label('email', _('E-mail address')) ?>

            <?php echo Form::email('email', '', array('class' => 'text-input 1/1', 'id' => 'email')) ?>

            <?php echo Form::label('password', _('Password/Passphrase')) ?>

            <?php
            echo Form::password('password', array('class' => 'text-input 1/1', 'id' => 'password')) ?>

            <?php echo Form::button(_('Sign In'), array('class' => 'btn 1/1 mb mt-', 'type'=>'submit')) ?>

    <?php echo Form::close() ?>

</div>
<div class="c">
    <?php echo HTML::link(action('\Project\Controllers\Admin\Account\Forgot'), _('Forgot password?')) ?>
</div>
<?php
$global_content = ob_get_clean();


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
// Includes
// --------------------------------------------------------------
include __DIR__ .'/../_masters/login.master.php';
