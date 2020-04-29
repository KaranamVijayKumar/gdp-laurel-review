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

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<h4 class=""><?php echo _('Contact Information') ?></h4>
<p>
    <?php echo _('Update your contact information. The following address will be used also as the shipping address.')?>
</p>

<?php echo Form::open(array('errors' => $errors)) ?>
<div class="layout ph-- mb">
    <div class="layout__item ">
        <?php echo Form::label('address', _('Address Line 1')) ?>

        <?php
        echo Form::text(
            'address',
            $user->profiles->findBy('name', 'address', $default)->value,
            array('class' => 'text-input 1/1 mb-', 'id' => 'address')
        ) ?>

        <?php
        echo Form::label(
            'address2',
            _('Address Line 2') . ' <small class=additional>(' . _('Optional') . ')</small>'
        ) ?>

        <?php
        echo Form::text(
            'address2',
            $user->profiles->findBy('name', 'address2', $default)->value,
            array('class' => 'text-input 1/1 mb-', 'id' => 'address2')
        ) ?>

    </div>
    <div class="layout__item pt 2/3 palm-1/1">
        <?php echo Form::label('city', _('City')) ?>

        <?php
        echo Form::text(
            'city',
            $user->profiles->findBy('name', 'city', $default)->value,
            array('class' => 'text-input 1/1 mb-', 'id' => 'city')
        ) ?>
    </div>
    <div class="layout__item 2/3 palm-1/1">
        <?php echo Form::label('state', _('State / Province / Region')) ?>

        <?php
        echo Form::text(
            'state',
            $user->profiles->findBy('name', 'state', $default)->value,
            array('class' => 'text-input 1/1 mb-', 'id' => 'state')
        ) ?>
    </div><!--
 --><div class="layout__item 1/3 palm-1/1">
        <?php echo Form::label('zip', _('ZIP / Postal Code')) ?>

        <?php
        echo Form::text(
            'zip',
            $user->profiles->findBy('name', 'zip', $default)->value,
            array('class' => 'text-input 1/1 mb-', 'id' => 'zip')
        ) ?>

    </div>
    <div class="layout__item  1/3 palm-1/1">
        <?php echo Form::label('country', _('Country')) ?>

        <?php
        echo Form::select(
            'country',
            require SP . 'config/countries.php',
            $user->profiles->findBy('name', 'country', $default)->value ?: 'US',
            array('id' => 'country', 'class' => '1/1')
        ) ?>
    </div>
    <div class="layout__item pt+ 2/3 palm-1/1">
        <?php echo Form::label('phone', _('Phone Number')) ?>

        <?php
        echo Form::text(
            'phone',
            $user->profiles->findBy('name', 'phone', $default)->value,
            array('class' => 'text-input 1/1 mb-', 'id' => 'phone')
        ) ?>

    </div>
    <div class="cf"></div>
    <div class="layout__item 1/5 palm-1/1 mt-">
        <?php echo Form::button(_('Save'), array('class' => 'btn 1/1', 'type'=>'submit')) ?>
    </div>
</div>
<?php
echo Form::close();

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
        <div class="flag__body gamma pv--">
            <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Account\Dashboard'), $title) ?>
            /
            <?php echo _('Contact Information') ?>
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

include __DIR__ .'/../_masters/page.master.php';
