<?php
/*!
 * create.subscribers.php v0.1
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
<?php echo Form::open(array('errors' => $errors)) ?>
    <p>
        <?php
        echo _(
            'Select a user or enter an email address to add a newsletter subscription. '.
            'Already subscribed users are not shown in the user list.'
        ) ?>
    </p>
    <div class="layout ph-- mv">
        <div class="layout__item 2/3 palm-1/1">

            <?php echo \Story\Form::label('user', _('User')) ?>
            <?php
            echo \Story\Form::select(
                'user',
                array('' => '') + $users,
                '',
                array(
                    'id' => 'user',
                    'class' => 'chosen-select  1/1',
                    'data-placeholder' => _('Select a user...')
                )
            ) ?>
        </div><!--
     --><div class="layout__item 2/3 palm-1/1 pv">
            <?php echo _('OR') ?>
        </div><!--
     --><div class="layout__item 2/3 palm-1/1 mb">

            <?php echo Form::label('email', _('E-mail address')) ?>
            <?php
            echo Form::text(
                'email',
                '',
                array('class' => 'text-input 1/1', 'id' => 'email')
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 mb+ pb">
            <?php echo Form::button(_('Add'), array('class' => 'btn btn--positive 1/1', 'type' => 'submit')) ?>
        </div>

    </div>
<?php echo Form::close() ?>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <?php echo HTML::link(action('\Project\Controllers\Admin\News\Subscribers'), _('Subscribers')) ?>
                /
                <?php echo $title ?>
            </div>
        </div>
    </div>
<?php

$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
?>
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">

<?php

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>

<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
