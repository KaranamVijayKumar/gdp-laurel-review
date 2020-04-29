<?php echo \Story\Form::open(
    array(
        'errors'=>$errors,
        'action' => action('\Project\Controllers\Newsletter\Unsubscribe')
    )
) ?>
    <div class="layout mv ph--">

        <div class="layout__item  u-2-of-3 u-1-of-1-palm">

            <?php echo \Story\Form::label('email', _('Email')) ?>

            <?php echo \Story\Form::email('email', '', array('class' => 'text-input  u-1-of-1', 'id' => 'u_email')) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item u-2-of-3 u-1-of-1-palm">
            <?php
            echo \Story\Form::button(
                _('Unsubscribe'),
                array('class' => 'btn btn--negative u-1-of-1-palm', 'type'=>'submit')
            ) ?>
        </div>
    </div>

<?php echo \Story\Form::close();
