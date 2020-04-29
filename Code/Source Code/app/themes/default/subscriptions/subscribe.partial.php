<?php
echo \Story\Form::open(
    array(
        'errors' => $errors,
        'id' => 'paypal-payment',
        'action' => action('\Project\Controllers\Subscriptions\Create')
        )
) ?>

<p>
    <?php echo \Story\Form::label('category', _('Category')) ?>

    <?php
    echo \Story\Form::select(
        'category',
        array('' => _('Select a Category')) + (array) $categories->lists('id', 'name'),
        '',
        array('class' =>'js-details-selector')
    ) ?>

    <?php
    echo \Story\Form::button(
        _('Add to Cart'),
        array('type' => 'submit', 'class' => 'btn btn--alert')
    ) ?>
</p>
<?php
echo \Story\Form::close() ?>


