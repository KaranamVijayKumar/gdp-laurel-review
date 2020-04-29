<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0">
            <em><?php echo sprintf(ngettext('%d subscription.', '%d subscriptions.', $total), $total) ?></em>
        </p>
        <ol class="item-list pt">
        <?php
            foreach ($items as $item) { ?>

                <?php
                $url =  action('\Project\Controllers\Admin\Subscriptions\Show', array($item->id));
                $status_url = action(
                    '\Project\Controllers\Admin\Subscriptions\Index',
                    array(
                        $item->status ? \Project\Models\Subscription::ENABLED : \Project\Models\Subscription::DISABLED,
                        $selectedExpiration
                    )
                );

                $description = new \Html2Text\Html2Text($item->description);
                $description = ellipsize($description->getText(), 500);
                $range = $item->starts->format('M j, Y') .' - ' . $item->expires->format('M j, Y');
                $mode = $item->getExpiresMode();
                $expires_url = action(
                    '\Project\Controllers\Admin\Subscriptions\Index',
                    array($selectedStatus, $mode)
                );
                ?>
                <li class="item-list__item flag mb-">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <?php echo $range ?>
                            </div>
                            <div class="media__body item-list__title">
                                <?php
                                echo \Story\HTML::link(
                                    $url,
                                    sprintf('%s: %s', $item->user_name ?: $item->user_email, $item->name)
                                ) ?>
                            </div>
                        </div>
                        <div class="item-list__description">

                            <a href="<?php echo $status_url ?>" class="btn btn--tiny btn--secondary">
                        <?php
                                if ($item->status) { ?>
                                    <span class="green"><?php echo _('Enabled') ?></span>
                        <?php
                                } else { ?>

                                    <span class="red"><?php echo _('Disabled') ?></span>
                        <?php
                                } ?>
                            </a>
                            <a href="<?php echo $expires_url ?>" class="btn btn--tiny btn--secondary">
                        <?php
                                switch ($mode) {
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
                            </a>
                            <br/>
                            <?php echo $description ? $description : '<em>'._('There are no notes.').'</em>' ?>
                        </div>
                    </div>
                </li>
            <?php
            } ?>

        </ol>
        <div class="c"><?php echo $pagination ?></div>
        <?php require_once __DIR__ . '/export.partial.php' ?>
    <?php
    } ?>
</div>
