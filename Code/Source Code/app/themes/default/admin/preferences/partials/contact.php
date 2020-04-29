<h4 class="mb0 content-hero i--delta orange"><?php echo _('Contact form') ?></h4>
<div class="layout">
    <div class="layout__item pt 1/1">
        <?php echo \Story\Form::label('contact_recipients', _('Recipients')) ?>

        <?php
        echo \Story\Form::text(
            'contact_recipients',
            config('contact_recipients'),
            array('class' => 'text-input 1/1', 'id' => 'contact_recipients')
        ) ?>
        <p class="mb0">
            <small>
                <?php
                echo _(
                    'All recepients will need to be comma separated.'.
                    ' The recepients will get a blind carbon copy (BCC).'
                ) ?>
            </small>
        </p>

    </div>
</div>
