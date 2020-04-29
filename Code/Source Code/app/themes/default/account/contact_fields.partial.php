<?php

use Story\Form;

?>
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
        array('class' => 'text-input  u-2-of-3 u-1-of-1-palm', 'id' => 'phone')
    ) ?>
</div><div class="layout__item  u-1-of-2 u-1-of-1-palm">
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
     --><div class="layout__item u-1-of-3 u-1-of-1-palm">
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

<div class="cf"></div>

