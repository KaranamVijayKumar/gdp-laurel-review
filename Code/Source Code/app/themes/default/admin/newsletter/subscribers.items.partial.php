<div id="results">
    <?php
    if (!count($items)) { ?>
        <p class="mb0"><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d subscriber.', '%d subscribers.', $total), $total) ?></em></p>
        <ol class="item-list pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\News\Subscribers@edit', array($item->id));
                $created = \Carbon\Carbon::createFromTimestamp($item->created);
                ?>
                <li class="item-list__item flag mb-">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <span title="<?php echo $created ?>"><?php echo $created->diffForHumans() ?></span>
                            </div>
                            <div class="media__body item-list__title">
                                <?php echo \Story\HTML::link($url, h($item->email)) ?>
                            </div>
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
