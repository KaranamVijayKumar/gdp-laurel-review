<h4 class="mb0 content-hero i--delta orange"><?php echo _('Subscriptions') ?></h4>
<div class="layout">
    <div class="layout__item pt 1/4 lap-1/3 palm-1/1">
        <?php
        echo \Story\Form::label('subscription_allow_renew_before', _('Allow renewal before expiration (days)')) ?>

        <?php
        echo \Story\Form::number(
            'subscription_allow_renew_before',
            config('subscription_allow_renew_before'),
            array(
                'class' => 'text-input 1/1',
                'id' => 'subscription_allow_renew_before',
                'min'   => '0',
                'max'   => '365',
                'step'  => '1',
            )
        ) ?>
        <p>
            <small>
                <?php
                echo _(
                    '<strong>Example</strong>: '.
                    'If users can renew their subscriptions 30 days before expiration, set the renewal to 30.'
                ) ?>
            </small>
        </p>
    </div><!--
 --><div class="layout__item pt 3/4 lap-2/3 palm-1/1">
        <?php
        echo \Story\Form::label(
            'subscription_renew_notify_days',
            _('Send expiration notifications in the following days')
        ) ?>

        <?php

        echo \Story\Form::select(
            'subscription_renew_notify_days[]',
            array_combine(
                range(1, config('subscription_allow_renew_before')),
                range(1, config('subscription_allow_renew_before'))
            ),
            config('subscription_renew_notify_days'),
            array(
                'class' => '9/10 chosen-select',
                'multiple' => 'multiple',
                'data-placeholder'=> _('Select some days')
            )
        ) ?>
        <p>
            <small>
                <?php
                echo _(
                    '<strong>Example</strong>: Selecting 1 and 25 will send a notification '.
                    'email on the 1st and 25th day when they can renew the subscription.<br> '
                )
                ?>
            </small>
        </p>
    </div>
</div>

