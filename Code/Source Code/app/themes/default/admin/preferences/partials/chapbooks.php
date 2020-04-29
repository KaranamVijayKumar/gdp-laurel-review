<h4 class="mb0 content-hero i--delta orange"><?php echo _('Chapbooks') ?></h4>
<div class="layout">
    <div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label(
            'latest_chapbook_price',
            sprintf(_('Latest Chapbook Price (%s)'), get_locale_info('currency_symbol'))
        ) ?>

        <?php echo \Story\Form::number(
            'latest_chapbook_price',
            config('latest_chapbook_price'),
            array(
                'class' => 'text-input 1/1',
                'id'    => 'latest_chapbook_price',
                'min'   => '0.01',
                'max'   => '1000000000',
                'step'  => '0.01'
            )
        ) ?>

    </div><!--
     --><div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label(
            'back_chapbook_price',
            sprintf(_('Previous Chapbook Price (%s)'), get_locale_info('currency_symbol'))
        ) ?>

        <?php echo \Story\Form::number(
            'back_chapbook_price',
            config('back_chapbook_price'),
            array(
                'class' => 'text-input 1/1',
                'id'    => 'back_chapbook_price',
                'min'   => '0.01',
                'max'   => '1000000000',
                'step'  => '0.01',
            )
        ) ?>

    </div><!--
 --><div class="layout__item pt 1/4 palm-1/1">
        <?php echo \Story\Form::label(
            'chapbook_tax',
            sprintf(_('Tax (%s)'), '%')
        ) ?>

        <?php echo \Story\Form::number(
            'chapbook_tax',
            config('chapbook_tax'),
            array(
                'class' => 'text-input 1/1',
                'id'    => 'chapbook_tax',
                'min'   => '0.00',
                'max'   => '100',
                'step'  => '0.01',
            )
        ) ?>

    </div>
</div>
