<h4 class="mb0  content-hero i--delta orange"><?php echo _('SMTP Server') ?></h4>
<div class="layout">
    <div class="layout__item pt 1/1">
        <?php echo \Story\Form::label('smtp', _('Use SMTP')) ?>

        <?php echo \Story\Form::select('smtp', array('0' => _('No'), '1' => _('Yes')), config('smtp'), array('id' => 'smtp')) ?>
    </div>
    <div class="layout__item pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('smtp_host', _('SMTP servers')) ?>

        <?php echo \Story\Form::text('smtp_host', config('smtp_host'), array('class' => 'text-input 1/1', 'id' => 'smtp_host')) ?>

    </div><!--
 --><div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label('smtp_secure', _('Encryption')) ?>

        <?php echo \Story\Form::select('smtp_secure', array('0' => _('None'), 'tls' => _('TLS'), 'ssl' => _('SSL')), config('smtp_secure'), array('id' => 'smtp_secure')) ?>

    </div><!--
 --><div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label('smtp_port', _('Port')) ?>

        <?php echo \Story\Form::number('smtp_port', config('smtp_port'), array('class' => 'text-input 1/1', 'id' => 'smtp_port', 'min' => 1, 'max' => '65535')) ?>

    </div><!--
 --><div class="layout__item pt 1/1 palm-1/1">
        <?php echo \Story\Form::label('smtp_auth', _('SMTP Authentication')) ?>

        <?php echo \Story\Form::select('smtp_auth', array('0' => _('No'), '1' => _('Yes')), config('smtp_auth'), array('id' => 'smtp_auth')) ?>
    </div>
    <div class="layout__item pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('smtp_username', _('SMTP Username')) ?>

        <?php echo \Story\Form::text('smtp_username', config('smtp_username'), array('class' => 'text-input 1/1', 'id' => 'smtp_username')) ?>

    </div><!--
 --><div class="layout__item pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('smtp_password', _('SMTP Password')) ?>

        <?php echo \Story\Form::password('smtp_password', array('class' => 'text-input 1/1', 'id' => 'smtp_password')) ?>

    </div>
</div>
