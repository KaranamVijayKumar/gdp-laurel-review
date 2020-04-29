<?php
/*!
 * delete.php v0.1
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
?>
    <h4 class=""><?php echo _('Delete Account') ?></h4>
    <?php
    if ($self) { ?>
        <p><?php
            echo sprintf(
                _("This action will permanently remove your account! This action is instant and cannot be recovered."),
                '<strong>' . h($user->profiles->findBy('name', 'name')->value) . '</strong>'
            ) ?></p>
        <p>
            <?php
            echo _('After deleting, you should will receive an email confirming that their account was removed.') ?>
        </p>
        <p class="red">
            <?php echo _('If you are the only administrator, make sure there is at least one more full administrator!') ?>
        </p>
    <?php
    } else { ?>
        <p><?php
            echo sprintf(
                _("This action will permanently remove %s's account! This action is instant and cannot be recovered."),
                '<strong>' . h($user->profiles->findBy('name', 'name')->value) . '</strong>'
            ) ?></p>
        <p>
            <?php echo _('After deleting, the user will receive an email confirming that their account was removed.') ?>
        </p>
        <p>
            <?php
            echo sprintf(
                _("User's email address: %s"),
                \Story\HTML::link('mailto:' . $user->email, h($user->email))
            ) ?>
        </p>
    <?php
    } ?>
<?php echo \Story\Form::open(array('class' => 'filter', 'method' => 'delete')) ?>
    <div class="layout ph-- mb">
        <div class="layout__item 1/5 palm-1/1">
            <?php
            echo \Story\Form::button(
                _('Delete Account'),
                array(
                    'class' => 'btn 1/1 btn--negative confirm',
                    'type' => 'submit',
                    'data-confirm' => _('This action cannot be recovered.')
                )
            ) ?>
        </div>
    </div>
<?php echo \Story\Form::close() ?>

<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--rev">
            <div class="flag__img">
                <?php echo \Story\HTML::gravatar($user->email, 32, '', 'mm'); ?>
            </div>
            <div class="flag__body gamma pv-">
                <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Users\Index'), $title) ?>
                /
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Users\Edit', array($user->id)),
                    h($user->profiles->findBy('name', 'name')->value ?: $user->email)
                ) ?>
                /
                <span class="red"><?php echo _('Delete Account') ?></span>
            </div>
        </div>
    </div>
<?php
$global_toolbar = ob_get_clean();

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

include __DIR__ . '/../_masters/page.master.php';
