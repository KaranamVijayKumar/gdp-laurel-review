<table class="1/1 table--borderless">
    <thead>
    <tr>
        <th class="1/12">
            <span class="label"><?php echo _('QTY') ?></span>
        </th>
        <th>
            <span class="label"><?php echo _('Item') ?></span>
        </th>
        <th class="1/6">
            <span class="label"><?php echo _('Type') ?></span>
        </th>
        <th class="1/8 tr">
            <span class="label"><?php echo _('Unit Price') ?></span>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($order->items as $item) { ?>

        <?php $item_data = $item->item_data ?>
        <tr>
            <td class="">
                <?php echo $item->quantity ?>
            </td>
            <td>
            <?php
                if ($item_data instanceof \Project\Support\Orders\LinkableInterface && $item_data->canLink()) { ?>

                    <?php echo \Story\HTML::link(
                        $item_data->getAdminLink(),
                        $item_data->getName()
                    ) ?>

            <?php
                } else { ?>

                    <?php echo $item_data->getName() ?>

            <?php
                } ?>

                <?php
                    if ($item_data instanceof \Project\Support\Orders\ShippableInterface) { ?>

                        <span class="i-flag-o green"></span>
                <?php
                    } ?>
            </td>
            <td>
                <?php echo $item_data->getOrderType() ?>
            </td>
            <td class="tr">
                <?php echo money_format('%n', $item->price) ?>
            </td>
        </tr>
        <?php
    } ?>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="tr pv0">
            <span class="label"><?php echo _('Sub Total') ?></span>
        </td>
        <td class="tr pv0">
            <?php echo money_format('%n', $order->sub_total) ?>
        </td>

    </tr>
    <tr>
        <td colspan="3" class="tr  pv0">
            <span class="label"><?php echo _('Tax') ?></span>
        </td>
        <td class="tr pv0">
            <?php echo money_format('%n', $order->tax) ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="tr  pv0">
            <span class="label"><?php echo _('Order Total') ?></span>
        </td>
        <td class="tr pv0">
            <strong><?php echo money_format('%n', $order->order_total) ?></strong>
        </td>
    </tr>
    </tbody>
</table>

<?php
if (count($order->items->getShippableItems()))  { ?>
    <div class="1/1 gray">
        <em><?php echo sprintf(_('Items marked %s requires shipping.'), '<span class="i-flag-o green"></span>') ?></em>
    </div>
<?php } ?>
