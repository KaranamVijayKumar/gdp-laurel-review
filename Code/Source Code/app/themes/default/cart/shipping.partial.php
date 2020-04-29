<?php

use Story\Form;

?>
<div class="flag">
    <div class="flag__img">
        <span class="icon-gift icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><?php echo _('Shipping Address') ?></h2>
        <p class="note u-mt0">
            Some items in your cart requires shipping. Please fill out your name,
            e-mail, shipping address and phone number.

        </p>
    </div>
</div>
<hr>
<div class="layout">
    <div class="layout__item u-1-of-2 u-1-of-1-palm">
        <?php echo Form::label('name', _('Name')) ?>

        <?php
        echo Form::text(
            'name',
            $user->profiles->findBy('name', 'name', $default)->value,
            array('class' => 'text-input u-1-of-1', 'id' => 'name')
        ) ?>

    </div><!--
    --><div class="layout__item u-1-of-2 u-1-of-1-palm mb">
        <?php echo Form::label('email', _('Email address'))?>

        <?php
        echo Form::text('email', $user->email, array('class' => 'text-input u-1-of-1', 'id' => 'email')) ?>

    </div>
    <div class="layout__item u-1-of-2 u-1-of-1-palm">
        <?php echo Form::label('address', _('Address Line 1')) ?>

        <?php
        echo Form::text(
            'address',
            $user->profiles->findBy('name', 'address', $default)->value,
            array('class' => 'text-input u-1-of-1', 'id' => 'address')
        ) ?>

        <?php
        echo Form::label(
            'address2',
            _('Address Line 2') . ' <small class="additional">(' . _('Optional') . ')</small>'
        ) ?>

        <?php
        echo Form::text(
            'address2',
            $user->profiles->findBy('name', 'address2', $default)->value,
            array('class' => 'text-input u-1-of-1', 'id' => 'address2')
        ) ?>
        <?php echo Form::label('phone', _('Phone Number')) ?>

        <?php
        echo Form::text(
            'phone',
            $user->profiles->findBy('name', 'phone', $default)->value,
            array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'phone')
        ) ?>
    </div><!--
 --><div class="layout__item u-1-of-2 u-1-of-1-palm">
        <?php echo Form::label('city', _('City')) ?>

        <?php
        echo Form::text(
            'city',
            $user->profiles->findBy('name', 'city', $default)->value,
            array('class' => 'text-input u-1-of-1', 'id' => 'city')
        ) ?>

        <div class="layout">
            <div class="layout__item u-2-of-3 u-1-of-1-palm">
                <?php echo Form::label('state', _('State / Province / Region')) ?>

                <?php
                echo Form::text(
                    'state',
                    $user->profiles->findBy('name', 'state', $default)->value,
                    array('class' => 'text-input u-1-of-1', 'id' => 'state')
                ) ?>
            </div><!--
             --><div class="layout__item u-1-of-3">
                <?php echo Form::label('zip', _('ZIP / Postal Code')) ?>

                <?php
                echo Form::text(
                    'zip',
                    $user->profiles->findBy('name', 'zip', $default)->value,
                    array('class' => 'text-input u-1-of-1', 'id' => 'zip')
                ) ?>

            </div>
        </div>

        <?php echo Form::label('country', _('Country')) ?>
        <?php
        echo Form::select(
            'country',
            require SP . 'config/countries.php',
            $user->profiles->findBy('name', 'country', $default)->value ?: 'US',
            array('id' => 'country', 'class' => 'u-1-of-1')
        ) ?>

    </div>
    <?php
    if (!\Story\Auth::check()) { ?>
        <hr/>
        <div class="layout__item u-1-of-1">
            <?php echo $sp->makeFields() ?>
        </div>
        <?php
    } ?>

</div>
