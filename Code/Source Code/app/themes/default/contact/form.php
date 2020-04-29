<?php echo \Story\Form::open(array('errors'=>$errors)) ?>

    <?php require __DIR__ . '/../_global/notifications.php'; ?>

    <div class="layout mv">

        <div class="layout__item u-1-of-2 u-1-of-1-palm">

            <?php echo \Story\Form::label('email', _('Email')) ?>

            <?php echo \Story\Form::email('email', $user->email, array('class' => 'text-input u-1-of-1', 'id' => 'email')) ?>
        </div><!--
        --><div class="layout__item u-1-of-2 u-1-of-1-palm">
            <?php echo \Story\Form::label('name', _('Name')) ?>

            <?php
            echo \Story\Form::text(
                'name',
                $user->profiles->findBy('name', 'name', $default)->value,
                array('class' => 'text-input u-1-of-1 ', 'id' => 'name')
            ) ?>

        </div>
        <div class="layout__item">
            <?php echo \Story\Form::label('message', _('Message')) ?>

            <?php
            echo \Story\Form::textarea(
                'message',
                '',
                array('class' => 'text-input u-1-of-1 ', 'id' => 'message', 'rows' => '10')
            ) ?>

        </div>
        <?php
        if (!\Story\Auth::check()) { ?>
            <div class="layout__item">
                <?php echo $sp->makeFields() ?>
            </div>
        <?php
        } ?>
        <div class="cf"></div>
        <div class="layout__item u-1-of-4 u-1-of-1-palm u-mt-">
            <?php echo \Story\Form::button(_('Send'), array('class' => 'btn u-1-of-1', 'type'=>'submit')) ?>
        </div>
    </div>

<?php echo \Story\Form::close();
