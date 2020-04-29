<?php echo \Story\Form::label('template', _('Template')) ?>
<?php

echo \Story\Form::select(
    'template',
    $templateList,
    isset($newsletter) ? $newsletter->template_id : key($templateList),
    array(
        'id' => 'template',
        'class' => 'chosen-select  1/3 lap-1/1 palm-1/1',
        'data-placeholder' => _('Select a template'),

    ) + (!$editable ? array('disabled' => 'disabled') : array())
) ?>
