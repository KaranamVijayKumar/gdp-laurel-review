<?php
if (!$order) { ?>
    <p class="gray mv">
        <?php
        echo _(
            'There are no order details. Maybe it was added by an administrator?'
        ); ?>
    </p>
<?php
} else { ?>

    <ul class="nav epsilon">
        <li class="1/4 lap-1/2 palm-1/1 pb-">
            <span class="label">
                <?php echo _('Order ID') ?>
            </span>
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Orders\Edit', array($order->id)),
                \Project\Models\Order::INVOICE_PREFIX . $order->id
            ) ?>
        </li><!--
     --><li class="1/4 lap-1/2 palm-1/1 pb-">
            <span class="label">
                <?php echo _('Status') ?>
            </span>
            <?php echo h(_($order->order_status))?>
        </li><!--
     --><li class="1/4 lap-1/2 palm-1/1 pb-">
            <span class="label">
                <?php echo _('Sub total') ?>
            </span>
            <?php echo money_format('%n', $order->sub_total) ?>
        </li><!--
     --><li class="1/4 lap-1/2 palm-1/1 pb-">
            <span class="label">
                <?php echo _('Order total') ?>
            </span>
            <?php echo money_format('%n', $order->order_total) ?>
        </li><!--
     --><li class="1/2 lap-1/2 palm-1/1 pv-">
            <span class="label">
                <?php echo _('Created') ?>
            </span>
                <?php
                echo $order->created->diffForHumans(
                ) ?>
                <br>
                <small class="gray">
                    <?php echo $order->created->toDayDateTimeString() ?>
                </small>
            </li>
    </ul>

<?php } ?>
