<?php if (!count($items)) { ?>



<?php } else { ?>
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }
        th,
        td{
            border:1px solid #ccc;
            padding:6px;
            text-align:left;
        }
    </style>
    <table cellpadding="0" cellspacing="0" border="0">
        <thead>
        <tr>
            <th style="width: 5%;text-align: center"><?php echo _('QTY') ?></th>
            <th><?php echo _('Item') ?></th>
            <th style="width: 20%;"><?php echo _('Type') ?></th>
            <th style="width: 10%;text-align: right"><?php echo _('Unit Price') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item) { ?>
            <tr>
                <td style="width: 5%;text-align: center"><?php echo $item->quantity ?></td>
                <td><?php echo $item->name ?></td>
                <td><?php echo $item->type ?></td>
                <td  style="text-align: right"><?php echo get_formatted_currency($item->amount, $item->currency) ?></td>
            </tr>
        <?php } ?>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">
                    <?php echo _('Sub Total') ?>
                </td>
                <td style="text-align: right">
                    <?php echo get_formatted_currency($sub_total, $currency) ?>
                </td>

            </tr>
            <tr>
                <td colspan="3" style="text-align: right">
                    <?php echo _('Tax') ?>
                </td>
                <td style="text-align: right">
                    <?php echo get_formatted_currency($tax, $currency) ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">
                    <?php echo _('Order Total') ?>
                </td>
                <td style="text-align: right">
                    <strong><?php echo get_formatted_currency($order_total, $currency) ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
    

<?php } ?>
