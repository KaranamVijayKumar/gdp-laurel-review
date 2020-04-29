<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0">
            <em><?php echo sprintf(ngettext('%d order.', '%d orders.', $total), $total) ?></em>
        </p>
        <ol class="item-list pt">
    <?php
        /** @var \Project\Models\Order $item */
        foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\Orders\Edit', array($item->id));

                $user = '<em>'.
                    \Story\HTML::link(
                        action(
                            '\Project\Controllers\Admin\Orders\Index',
                            array($selectedOrderStatus)
                        ) . '?'.http_build_query(
                            array('q' => $item->user_email)
                        ),
                        $item->user_name ?: $item->user_email
                    ).'</em>';

                ?>
                <li class="item-list__item flag mb-">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">

                            </div>
                            <div class="media__body item-list__title">
                                <?php
                                echo \Story\HTML::link(
                                    $url,
                                    h($item->orderId())
                                ) ?>
                            </div>
                        </div>
                        <div class="item-list__description">
                            <?php echo $user ?>,
                            <span title="<?php echo $item->created ?>">
                                <?php echo $item->created->diffForHumans() ?>
                            </span>,
                            <?php echo get_formatted_currency($item->order_total, $item->currency) ?>
                            <br/>
                            <?php
                            echo \Story\HTML::link(
                                action('\Project\Controllers\Admin\Orders\Index', array($item->order_status)) .
                                ($query ? '?' . http_build_query(array('q' => $query)) : ''),
                                _($item->order_status),
                                array('class' => 'btn btn--tiny btn--secondary')
                            ) ?>
                            <br/>
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
