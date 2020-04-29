
<div class="layout epsilon">


    <div class="layout__item 1/4 lap-1/2 palm-1/1 pb-">
        <span class="label">
            <?php echo _('Status') ?>
        </span>
        <span class="<?php echo $subscription->status ? "green" : "red" ?>">
            <?php echo $subscription->status ? _('Enabled') : _('Disabled') ?>
        </span>
        /
        <?php
        switch ($subscription->getExpiresMode()) {
            case \Project\Models\Subscription::ACTIVE:
                echo '<span class="green">'. _('Active'). '</span>';
                break;
            case \Project\Models\Subscription::UPCOMING:
                echo '<span class="orange">'. _('Upcoming'). '</span>';
                break;
            default:
                echo '<span class="gray">'. _('Expired').'</span>';
                break;
        }
        ?>
    </div><!--
 --><div class="layout__item 1/4 lap-1/2 palm-1/1 pb-">
        <span class="label">
            <?php echo _('Category') ?>
        </span>
        <?php
        if (has_access('admin_subscriptions_categories_edit') && $subscription->subscription_category_id) { ?>

            <?php
            echo \Story\HTML::link(
                action(
                    '\Project\Controllers\Admin\Subscriptions\Categories@edit',
                    array($subscription->subscription_category_id)
                ),
                h($subscription->name)
            ) ?>

        <?php
        } else { ?>

            <?php echo h($subscription->name) ?>

        <?php
        } ?>
    </div><!--
 --><div class="layout__item 1/4 lap-1/2 palm-1/1 pb-">
        <span class="label">
            <?php echo _('Interval') ?>
        </span>
        <?php echo sprintf(ngettext('%s Month', '%s Months', $subscription->interval), $subscription->interval) ?>
    </div><!--
 --><div class="layout__item 1/4 lap-1/2 palm-1/1 pb-">
        <span class="label">
            <?php echo _('Amount') ?>
        </span>
        <?php echo money_format('%n', $subscription->amount) ?>
    </div><!--
 --><div class="layout__item 1/2 lap-1/2 palm-1/1 pv">
        <span class="label">
            <?php echo _('Starts') ?>
        </span>
        <?php
        echo $subscription->starts->diffForHumans(
        ) ?>
        <br>
        <small class="gray">
            <?php echo $subscription->starts->toDayDateTimeString() ?>
        </small>
    </div><!--
 --><div class="layout__item 1/2 lap-1/2 palm-1/1 pv">
        <span class="label">
            <?php echo _('Expires') ?>
        </span>
        <?php
        if (!$subscription->isCurrent() && !$subscription->isUpcoming()) { ?>
            <span class="red">
                <?php echo sprintf('Expired %s', $subscription->expires->diffForHumans()) ?>
            </span>
            <br>
            <small class="red">
                <?php echo $subscription->expires->toDayDateTimeString() ?>
            </small>
        <?php
        } else { ?>

            <?php
            echo $subscription->expires->diffForHumans(
            ) ?>
            <br>
            <small class="gray">
                <?php echo $subscription->expires->toDayDateTimeString() ?>
            </small>

        <?php
        } ?>

        <?php
        if ($subscription->canAdminRenew($upcomingSubscription)) { ?>
            <div>
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Subscriptions\Edit', array($subscription->id)),
                    _('Renew available'),
                    array('class' =>'btn btn--tiny btn--positive')
                ) ?>
            </div>
        <?php
        } ?>
    </div>

    <?php
    if ($subscription->description) { ?>
        <div class="layout__item 1/1 pv-">
            <span class="label">
            <?php echo _('Notes') ?>

            </span>
            <div class="zeta">
                <?php echo $subscription->description ?>
            </div>
        </div>
    <?php
    } ?>
</div>
