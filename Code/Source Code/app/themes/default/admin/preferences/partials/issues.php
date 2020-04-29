<h4 class="mb0 content-hero i--delta orange"><?php echo _('Issues') ?></h4>
<div class="layout">
    <div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label(
            'latest_issue_price',
            sprintf(_('Latest Issue Price (%s)'), get_locale_info('currency_symbol'))
        ) ?>

        <?php echo \Story\Form::number(
            'latest_issue_price',
            config('latest_issue_price'),
            array(
                'class' => 'text-input 1/1',
                'id'    => 'latest_issue_price',
                'min'   => '0.01',
                'max'   => '1000000000',
                'step'  => '0.01'
            )
        ) ?>

    </div><!--
     --><div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label(
            'back_issue_price',
            sprintf(_('Back Issue Price (%s)'), get_locale_info('currency_symbol'))
        ) ?>

        <?php echo \Story\Form::number(
            'back_issue_price',
            config('back_issue_price'),
            array(
                'class' => 'text-input 1/1',
                'id'    => 'back_issue_price',
                'min'   => '0.01',
                'max'   => '1000000000',
                'step'  => '0.01',
            )
        ) ?>

    </div><!--
 --><div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label(
            'issue_tax',
            sprintf(_('Tax (%s)'), '%')
        ) ?>

        <?php echo \Story\Form::number(
            'issue_tax',
            config('issue_tax'),
            array(
                'class' => 'text-input 1/1',
                'id'    => 'issue_tax',
                'min'   => '0.00',
                'max'   => '100',
                'step'  => '0.01',
            )
        ) ?>

    </div>
</div>
