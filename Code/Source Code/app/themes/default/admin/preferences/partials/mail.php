<h4 class="mb0 content-hero i--delta orange"><?php echo _('Mail: Send e-mail as') ?></h4>
<div class="layout">
    <div class="layout__item pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('mail_from', _('E-mail address')) ?>

        <?php echo \Story\Form::email('mail_from', config('mail_from'), array('class' => 'text-input 1/1', 'id' => 'mail_from')) ?>

    </div><!--
 --><div class="layout__item pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('mail_from_name', _('Sender\'s name')) ?>

        <?php echo \Story\Form::text('mail_from_name', config('mail_from_name'), array('class' => 'text-input 1/1', 'id' => 'mail_from_name')) ?>

    </div>
</div>
