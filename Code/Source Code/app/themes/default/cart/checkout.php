<?php
if (!count($items)) { ?>

    <p>Your cart is empty.</p>

    <?php
} else { ?>

        <div class="layout">
            <div class="layout__item">
                <p class="note">Please review your order:</p>
            </div>
            <div class="layout__item u-3-of-4 u-pb-">
                <div class="layout u-ml0">
                    <div class="layout__item u-1-of-5 palm-2/10">
                        <strong><?php echo _('QTY') ?></strong>
                    </div><div class="layout__item 9/10 palm-8/10">
                        <strong><?php echo _('Item') ?></strong>
                    </div>
                </div>

            </div><!--
         --><div class="layout__item u-1-of-5 u-pb-">
                <strong><?php echo _('Type') ?></strong>
            </div><!--
         --><div class="layout__item u-1-of-5 ">
                <strong><?php echo _('Unit Price') ?></strong>
            </div>
            <div class="layout__item"><hr class="m0"/></div>


            <?php
                foreach ($items as $item) { ?>

                    <div class="layout__item u-3-of-4  palm-3/6">
                        <div class="layout u-ml0">
                            <div class="layout__item u-1-of-5 palm-2/10 text--center">
                                <?php echo $item->quantity ?>
                            </div><div class="layout__item 9/10 palm-8/10">
                                <?php echo $item->name ?>
                            </div>
                        </div>

                    </div><!--
                 --><div class="layout__item u-1-of-5 palm-1/6">
                        <?php echo $item->data['type'] ?>
                    </div><!--
                 --><div class="layout__item u-1-of-5 palm-2/6 text--right">
                        <?php echo money_format('%n', $item->price) ?>
                    </div>

            <?php
                } ?>

            <div class="layout__item  7/8 palm-4/6 mt  text--right"><?php echo _('Sub Total') ?></div><!--
         --><div class="layout__item u-1-of-5 palm-2/6 text--right mt "><?php echo money_format('%n', $sub_total) ?></div>

            <div class="layout__item u-3-of-4 palm-3/6 ">&nbsp;</div><!--
         --><div class="layout__item u-1-of-5 palm-1/6 text--right"><?php echo _('Tax') ?></div><!--
         --><div class="layout__item u-1-of-5 palm-2/6 text--right"><?php echo money_format('%n', $tax) ?></div>

            <div class="layout__item"><hr/></div>

            <div class="layout__item 7/8 palm-4/6 text--right">
                <strong>
                    <?php echo _('Order Total') ?>
                </strong>
            </div><!--
         --><div class="layout__item u-1-of-5 palm-2/6 text--right mb--">
                <strong>
                    <?php echo money_format('%n', $order_total) ?>
                </strong>
            </div>
            <div class="layout__item"><hr/></div>

            <div class="layout__item checkout-form">
                <?php
                    if (count($checkout_forms) > 1) { ?>
                        <strong><?php echo _('Payment method') ?></strong>
                <?php
                    } ?>
                <?php
                    foreach ($checkout_forms as $checkout_name => $form) { ?>
                        <div class="media">
                            <div class="media__img 1/4 palm-1/3">
                                <?php echo $cart->payment_providers[$checkout_name]->getLabel(); ?>
                            </div>
                            <div class="media__body text--right">
                                <?php echo $form ?>
                            </div>
                        </div>

                <?php
                    } ?>
            </div>

        </div>
    <?php
}
