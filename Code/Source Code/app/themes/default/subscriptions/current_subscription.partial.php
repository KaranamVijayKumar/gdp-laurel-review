<table class="table table--striped table--borderless u-1-of-1 u-mt">
    <tbody>

    <tr>
        <td><?php echo _('Status') ?></td>
        <td>
            <?php
            if ($subscription->status) { ?>

                <span class="text--positive">
                    <?php echo _('Enabled') ?>
                </span>

            <?php
            } else { ?>

                <span class="text--negative">
                    <?php echo _('Disabled') ?>
                </span>

            <?php
            } ?>

        </td>
    </tr>

    <tr>
        <td><?php echo _('Expiration') ?></td>
        <td>
            <strong class="<?php echo $canRenewSubscription ? 'text--negative':'' ?>">
                <?php echo $subscription->expires->diffForHumans() ?>
            </strong>
        </td>
    </tr>
    <?php
    if ($canRenewSubscription) { ?>

        <tr>
            <td><?php echo _('Renewal') ?></td>
            <td>
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Subscriptions\Create', array('renew')),
                    _('Renew now'),
                    array('class' => 'btn btn--alert u-pv0')
                ) ?>
            </td>
        </tr>

    <?php
    } ?>
    <tr>
        <td><?php echo _('Period') ?></td>
        <td>
            <?php echo $subscription->starts->toDayDateTimeString() ?>
            -
            <?php echo $subscription->expires->toDayDateTimeString() ?>
        </td>
    </tr>
    <tr>
        <td><?php echo _('Interval') ?></td>
        <td>
            <?php echo sprintf(ngettext('%s Month', '%s Months', $subscription->interval), $subscription->interval) ?>
        </td>
    </tr>
    <tr>
        <td><?php echo _('Category') ?></td>
        <td><?php echo _($subscription->name) ?></td>
    </tr>
<?php
if (isset($subscription->order->id)) { ?>
    <tr>
        <td><?php echo _('Order status') ?></td>
        <td><?php echo _($subscription->order->order_status) ?></td>
    </tr>
    <?php
} ?>
    <tr>
        <td><?php echo _('Cancellation') ?></td>
        <td>
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Subscriptions\Cancel', array($subscription->id)),
                _('Click here to cancel this subscription'),
                array('class' => 'confirm', 'data-confirm' => _('This action is instant and cannot be recovered.'))
            ) ?>
        </td>
    </tr>
    </tbody>
</table>

