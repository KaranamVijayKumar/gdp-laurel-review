<?php
if (!count($cart->all())) { ?>

    <div class="notifications">
        <ol>
            <li class="notification">
                <div class="notification__img icon-basket"></div>
                <div class="notification__body">
                    <?php echo _('Your Shopping Cart is empty.') ?>
                </div>

            </li>
        </ol>
    </div>

    <?php
} else { ?>

        <div class="flag">
            <div class="flag__img">
                <span class="icon-basket icon--huge"></span>
            </div>
            <div class="flag__body">
                <h2 class="u-mv0"><?php echo _('Your items') ?></h2>
                <p class="note u-mt0">
                    Please review your cart:

                </p>
            </div>
        </div>
        <hr>
        <table class="table--borderless u-1-of-1 u-mb0">
            <thead>
            <tr>
                <th style="width: 1%">&nbsp;</th>
                <th class="text--center" style="width: 5%"><?php echo _('QTY') ?></th>
                <th><?php echo _('Item') ?></th>
                <th  class="u-1-of-5 text--right"><?php echo _('Type') ?></th>
                <th  class="u-1-of-5 text--right"><?php echo _('Unit Price') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="5"><hr class="u-m0"/></td>
            </tr>
        <?php
            foreach ($cart->all() as $item) { ?>

                <?php $type_payload = $item->type_payload; ?>
                <tr>
                    <td class="text--center">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Cart\Remove', array($item->id)),
                            '<strong>&times;</strong>',
                            array(
                                'class' => 'text--negative u-mv0 confirm text--nodecoration',
                                'title' => _('Remove from cart'),
                            )
                        ) ?>
                    </td>
                    <td class="text--center"><?php echo $item->quantity ?></td>

                    <td class="delta">
                        <strong>
                <?php
                        if ($type_payload instanceof \Project\Support\Orders\LinkableInterface
                            && $type_payload->canLink()) { ?>

                            <?php $link = $type_payload->getLink(); ?>

                            <?php
                            echo $link ? \Story\HTML::link(
                                $link,
                                $type_payload->getName()
                            ) : $type_payload->getName() ?>
                <?php
                        } else { ?>

                            <?php echo $type_payload->getName() ?>

                <?php
                        } ?>
                        </strong>
                    </td>
                    <td class="text--right"><?php echo $type_payload->getOrderType() ?></td>
                    <td class="text--right"><?php echo get_formatted_currency($item->price, $item->currency) ?></td>
                </tr>

        <?php
            } ?>
            <tr>
                <td colspan="5"><hr class="m0"/></td>
            </tr>
            <tr>

                <td colspan="4" class="u-1-of-5">
                    <div class="media">
                        <div class="media__img">
                            <?php
                            echo \Story\HTML::link(
                                action('\Project\Controllers\Cart\EmptyCart'),
                                _('Empty Cart'),
                                array('class' => 'confirm')
                            ) ?>
                        </div>
                        <div class="media__body text--right">
                            <strong><?php echo _('Sub Total:') ?></strong>
                        </div>
                    </div>

                </td>
                <td  class="text--right">
                    <strong><?php echo get_formatted_currency($final_prices['sub_total'], $currency) ?></strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text--right">
                    <strong><?php echo _('Tax:') ?></strong>
                </td>
                <td  class="text--right">
                    <strong><?php echo get_formatted_currency($final_prices['tax'], $currency) ?></strong>
                </td>
            </tr>

            <tr>
                <td colspan="4" class="text--right">
                    <strong><?php echo _('ORDER TOTAL:') ?></strong>
                </td>
                <td  class="text--right">
                    <strong><?php echo get_formatted_currency($final_prices['order_total'], $currency) ?></strong>
                </td>
            </tr>
            </tbody>
        </table>
    <?php
}
