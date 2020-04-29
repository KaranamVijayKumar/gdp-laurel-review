<div class="flag u-mt">
    <div class="flag__body">
        <h2 class="u-mv0 text--alert"><?php echo _('Payment &amp; Checkout') ?></h2>
    </div>
</div>
<hr>
<div class="layout u-mb">
    <div class="layout__item  u-1-of-4 u-1-of-3-palm">
        <?php echo $payment->getLabel(); ?>
    </div><!--
    --><div class="layout__item u-3-of-4 u-1-of-1-palm text--right">
        <?php
        echo \Story\Form::button(
            '<span>' . _('Continue with Paypal') .'</span> <i class="beta icon-angle-double-right ml"></i>',
            array(
                'type' => 'submit',
                'class' => 'btn u-mt- u-1-of-1-palm',
                'data-wait-text' => _("Please wait ...")
            )
        ) ?>
    </div>
</div>
