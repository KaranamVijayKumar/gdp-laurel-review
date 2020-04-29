<h4 class="mv0">
    <?php echo _('Create Subscription') ?>
</h4>
<p class="gray mb-">
    <?php
    echo _(
        'Select a subscription category. Upon accepting the submission the user will be given a free subscription.'
    ) ?>
</p>
<?php echo \Story\Form::label('subscription_category', _('Category')) ?>
<?php
echo \Story\Form::select(
    'subscription_category',
    array('' => 'Without subscription') + subscription_categories(),
    key(subscription_categories()),
    array(
        'id' => 'subscription_category',
        'class' => '1/3 lap-1/1 palm-1/1',
        'data-placeholder' => _('Select a subscription category')
    )
) ?>
