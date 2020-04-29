<?php echo \Story\Form::label('template', _('Template')) ?>
<?php
echo \Story\Form::select(
    'template',
    $templateList,
    key($templateList),
    array(
        'id' => 'subscription_category',
        'class' => '1/3 lap-1/1 palm-1/1  js-fieldloader',
        'data-placeholder' => _('Select a subscription category'),
        'data-fieldloader-url' => action('\Project\Controllers\Admin\Submissions\Template', array($submission->id))
    )
) ?>
