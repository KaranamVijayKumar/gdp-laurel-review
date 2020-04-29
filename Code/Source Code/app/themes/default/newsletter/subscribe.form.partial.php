<?php echo \Story\Form::open(
    array(
        'errors' => $errors,
        'action' => action('\Project\Controllers\Newsletter\Subscribe')
    )
) ?>

    <div class="layout u-mv">

        <div class="layout__item u-2-of-3 u-1-of-1-palm">

            <?php echo \Story\Form::label('email', _('Email')) ?>

            <?php echo \Story\Form::email('email', '', array('class' => 'text-input u-1-of-1', 'id' => 's_email')) ?>

        </div>
        <div class="cf"></div>
        <div class="layout__item u-2-of-3 u-1-of-1-palm">
            <?php
            echo \Story\Form::button(
                _('Subscribe'),
                array('class' => 'btn btn--positive u-1-of-1-palm', 'type' => 'submit')
            ) ?>
        </div>
    </div>

<?php echo \Story\Form::close();
