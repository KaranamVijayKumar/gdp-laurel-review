<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d category.', '%d categories.', $total), $total) ?></em></p>
        <ol class="item-list  pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url = action('\Project\Controllers\Admin\Subscriptions\Categories@edit', array($item->id));
                $name = h($item->name);
//                $created = \Carbon\Carbon::createFromTimestamp($item->created);
                $item_description = new \Html2Text\Html2Text($item->description);
                $count = isset($item->submissionCount) ? (int)$item->submissionCount : 0;
                $item_description = $item_description->getText();
                ?>
                <li class="item-list__item flag">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <?php echo money_format('%n', $item->amount) ?>,
                                <?php
                                echo !$count ? _('No subscriptions') : sprintf(
                                    ngettext('%s subscription', '%s subscriptions', $count),
                                    $count
                                ) ?>,
                                <?php echo $item->status ? _('Active') : _('Inactive') ?>
                            </div>
                            <?php
                            echo \Story\HTML::link(
                                $url,
                                $name,
                                array('class' => 'item-list__title media__body')
                            ) ?>
                        </div>
                        <div class="item-list__description">
                            <?php
                            echo $item_description ? $item_description : '<em>' . _(
                                'No description available.'
                            ) . '</em>' ?>
                        </div>
                    </div>
                </li>
            <?php
            } ?>
        </ol>
        <div class="c"><?php echo $pagination ?></div>
    <?php
    } ?>
</div>
