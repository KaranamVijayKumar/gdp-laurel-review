<?php echo \Story\Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data')) ?>
<div class="layout">
    <div class="layout__item 1/4 lap-1/2 palm-1/1 pb-">
        <?php echo \Story\Form::label('status', _('Status')) ?>
        <?php
        echo \Story\Form::select(
            'status',
            array('1' => _('Enabled'), '0' => _('Disabled')),
            $subscription->status,
            array('id' => 'status')
        ) ?>
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
 --><div class="layout__item 1/4 lap-1/2 palm-1/1 pb- epsilon">
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
 --><div class="layout__item 1/4 lap-1/2 palm-1/1 pb- epsilon">
        <span class="label">
            <?php echo _('Interval') ?>
        </span>
        <?php echo sprintf(ngettext('%s Month', '%s Months', $subscription->interval), $subscription->interval) ?>
    </div><!--
 --><div class="layout__item 1/4 lap-1/2 palm-1/1 pb- epsilon">
        <span class="label">
            <?php echo _('Amount') ?>
        </span>
        <?php echo money_format('%n', $subscription->amount) ?>
    </div>
    <div class="layout__item 1/2 lap-1/2 palm-1/1 pv epsilon">
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
 --><div class="layout__item 1/2 lap-1/2 palm-1/1 pv epsilon">
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

            <div class="spoken-form">
                <?php echo \Story\Form::checkbox('renew', 1, false, array('id' => 'renew')) ?>
                <?php echo \Story\Form::label('renew', _('Renew')) ?>
                <?php echo \Story\Form::label('category', _('for')) ?>
                <?php
                echo \Story\Form::select(
                    'category',
                    $categories,
                    '',
                    array('id' => 'category', 'class' => '  1/3 lap-1/1 palm-1/1')
                ) ?>

            </div>
        <?php
        } ?>
    </div>

    <div class="layout__item 1/1 pt-">
        <?php
        echo \Story\Form::label(
            'description',
            _('Notes') . ' <small class=additional>(' . _('Optional') . ')</small>'
        ) ?>
        <?php
        echo \Story\Form::textarea(
            'description',
            $subscription->attributes['description'],
            array(
                'rows'                    => '5',
                'class'                   => 'text-input 1/1',
                'id'                      => 'description',
                'placeholder'             => _('You can add notes. Only admins will see it.'),
            )
        ) ?>
    </div>
</div>
<div class="cf"></div>
<div class="pb pt-">
    <?php
    echo \Story\Form::button(
        _('Save'),
        array('class' => 'btn 1/5 palm-1/1', 'type' => 'submit')
    ) ?>
    <?php
    if (has_access('admin_subscriptions_show')) { ?>

        <?php
        echo \Story\HTML::link(
            action('\Project\Controllers\Admin\Subscriptions\Show', array($subscription->id)),
            _('Cancel'),
            array('class' =>'mh')
        ) ?>

    <?php
    } ?>
</div>
<?php echo \Story\Form::close() ?>
