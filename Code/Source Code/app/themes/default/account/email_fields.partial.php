<?php

use Story\Form;

?>
<p>
    <?php echo Form::label('name', _('Name')) ?>

    <?php
    echo Form::text(
        'name',
        $user->profiles->findBy('name', 'name', $default)->value,
        array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'name')
    ) ?>

</p>
<p class="pt">
    <?php echo Form::label('email', _('Email address'))?>

    <?php
    echo Form::text('email', $user->email, array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'email')) ?>

</p>
