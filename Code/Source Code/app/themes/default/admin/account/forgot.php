<?php
/*!
 * forgot.php v0.1
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
?>
<div class="content mb-">
    <?php echo Form::open() ?>

    <div class="layout">
        <div class="layout__item">
            <?php echo Form::label('email', _('E-mail address')) ?>

            <?php echo Form::email('email', '', array('class' => 'text-input 1/1', 'id' => 'email')) ?>

        </div>

        <div class="layout__item mt mb">
            <?php echo Form::button(_('Reset Password'), array('class' => 'btn 1/1', 'type'=>'submit')) ?>
        </div>

    </div>
    <?php echo Form::close() ?>

</div>
<div class="c">
    <?php echo HTML::link(action('\Project\Controllers\Admin\Auth'), _('Sign In')) ?>
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

