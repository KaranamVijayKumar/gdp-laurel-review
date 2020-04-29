<table class="table table--striped table--borderless u-1-of-1 u-mt">
    <tbody>

    <tr>
        <td><?php echo _('Status') ?></td>
        <td>
            <?php
            if ($upcomingSubscription->status) { ?>

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
        <td><?php echo _('Period') ?></td>
        <td>
            <?php echo $upcomingSubscription->starts->toDayDateTimeString() ?>
            -
            <?php echo $upcomingSubscription->expires->toDayDateTimeString() ?>
        </td>
    </tr>
    <tr>
        <td><?php echo _('Interval') ?></td>
        <td>
            <?php
            echo sprintf(
                ngettext(
                    '%s Month',
                    '%s Months',
                    $upcomingSubscription->interval
                ),
                $upcomingSubscription->interval
            ) ?>
        </td>
    </tr>
    <tr>
        <td><?php echo _('Category') ?></td>
        <td><?php echo _($upcomingSubscription->name) ?></td>
    </tr>
    <?php
    if (isset($upcomingSubscription->order->id)) { ?>
        <tr>
            <td><?php echo _('Order status') ?></td>
            <td><?php echo _($upcomingSubscription->order->order_status) ?></td>
        </tr>
    <?php
    } ?>
    <tr>
        <td><?php echo _('Cancellation') ?></td>
        <td>
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Subscriptions\Cancel', array($upcomingSubscription->id)),
                _('Click here to cancel this subscription'),
                array('class' => 'confirm', 'data-confirm' => _('This action is instant and cannot be recovered.'))
            ) ?>
        </td>
    </tr>
    </tbody>
</table>

