<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\Form;

ob_start();
?>
    <h4 class="mb- mt content-hero"><?php echo _('User') ?></h4>
<?php include __DIR__ . '/user.partial.php'; ?>
    <div class="flag flag--rev mb- mt content-hero">
        <div class="flag__img">
            <?php
            if (has_access('admin_subscriptions_show')) { ?>

                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Subscriptions\Show', array($subscription->id)),
                    _('Overview'),
                    array('class' =>'btn btn--tiny btn--secondary')
                ) ?>

            <?php
            } ?>
        </div>
        <h4 class="flag__body">
            <?php echo _('Subscription') ?>
        </h4>
    </div>
<?php include __DIR__ . '/subscription_edit.partial.php'; ?>
<h5 class="mb0 content-hero--small">Notes</h5>
<ul class="gray mb mh0 pv0 pl pr0">
    <li><?php echo _('Subscription Notes are only visible to administrators.') ?></li>
    <li><?php echo _('You can renew an the current subscription without the user paying the subscription fee by checking
        the <q>Renew</q> checkbox (available when the subscription can be renewed).') ?>
    </li>

    <li>
        <?php
        echo _(
            'In order to change a category, '.
            'delete subscription and make a new subscription with the desired category'
        ) ?>
    </li>
</ul>

<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <div class="media media--rev">
                <?php
                if (has_access('delete_admin_subscriptions_delete')) { ?>
                    <div class="media__img">
                        <?php
                        echo Form::open(
                            array(
                                'method' => 'delete',
                                'class' => 'filter',
                                'action' => action(
                                    '\Project\Controllers\Admin\Subscriptions\Delete',
                                    array($subscription->id)
                                )
                            )
                        )
                        ?>
                        <button class="btn btn--negative confirm i-trash-o" name="action" value="trash"
                                type="submit"> <?php echo _('Delete') ?></button>
                        <?php echo Form::close() ?>
                    </div>
                    <?php
                } ?>
                    <div class="media__body">
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Admin\Subscriptions\Index'),
                            _('Subscriptions')
                        ) ?>
                        /
                        <?php
                        echo \Story\HTML::link(
                            action('\Project\Controllers\Admin\Subscriptions\Show', array($subscription->id)),
                            $this->subscription_title
                        ) ?>
                        /
                        <?php echo $title ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
?>

<?php

$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
// extra head

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
// extra footer

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
