<?php
/*!
 * contact.php v0.1
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
<h4><?php echo _('Contact and Shipping Address') ?></h4>
<p>
    <?php
    echo _(
        'Update the user\'s contact information. The following address will be used also as the shipping address.'
    ) ?>
</p>
<?php echo \Story\Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mb">
        <div class="layout__item ">
            <?php echo \Story\Form::label('address', _('Address Line 1')) ?>
            <?php
            echo \Story\Form::text(
                'address',
                $user->profiles->findBy('name', 'address', $defaultProfile)->value,
                array('class' => 'text-input 1/1  mb-', 'id' => 'address')
            ) ?>
            <?php
            echo \Story\Form::label(
                'address2',
                _('Address Line 2') . ' <small class=additional>(' . _('Optional') . ')</small>'
            ) ?>
            <?php
            echo \Story\Form::text(
                'address2',
                $user->profiles->findBy('name', 'address2', $defaultProfile)->value,
                array('class' => 'text-input  1/1  mb-', 'id' => 'address2')
            ) ?>
        </div>
        <div class="layout__item pt 2/3 palm-1/1">
            <?php echo \Story\Form::label('city', _('City')) ?>
            <?php
            echo \Story\Form::text(
                'city',
                $user->profiles->findBy('name', 'city', $defaultProfile)->value,
                array('class' => 'text-input  1/1  mb-', 'id' => 'city')
            ) ?>
        </div>
        <div class="layout__item 2/3 palm-1/1">
            <?php echo \Story\Form::label('state', _('State / Province / Region')) ?>
            <?php
            echo \Story\Form::text(
                'state',
                $user->profiles->findBy('name', 'state', $defaultProfile)->value,
                array('class' => 'text-input  1/1  mb-', 'id' => 'state')
            ) ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo \Story\Form::label('zip', _('ZIP / Postal Code')) ?>
            <?php
            echo \Story\Form::text(
                'zip',
                $user->profiles->findBy('name', 'zip', $defaultProfile)->value,
                array('class' => 'text-input  1/1  mb-', 'id' => 'zip')
            ) ?>
        </div>
        <div class="layout__item  1/3 palm-1/1">
            <?php echo \Story\Form::label('country', _('Country')) ?>
            <?php
            echo \Story\Form::select(
                'country',
                require SP . 'config/countries.php',
                $user->profiles->findBy('name', 'country', $defaultProfile)->value ?: 'US',
                array('id' => 'country', 'class' => '1/1')
            ) ?>
        </div>
        <div class="layout__item pt+ 2/3 palm-1/1">
            <?php echo \Story\Form::label('phone', _('Phone Number')) ?>
            <?php
            echo \Story\Form::text(
                'phone',
                $user->profiles->findBy('name', 'phone', $defaultProfile)->value,
                array('class' => 'text-input 1/1', 'id' => 'phone')
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php echo \Story\Form::button(_('Save'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
        </div>
    </div>
<?php echo \Story\Form::close() ?>

<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
$user_subtitle = _('Contact and Shipping Address');
require_once __DIR__ . '/toolbar.partial.php';
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
