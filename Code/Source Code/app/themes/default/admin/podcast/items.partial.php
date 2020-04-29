<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0">
            <em><?php echo sprintf(ngettext('%d Podcast.', '%d Podcast.', $total), $total) ?></em>
        </p>
        <ol class="item-list pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\Pages\Podcast@edit', array($item->id));
                $description = new \Html2Text\Html2Text($item->description);
                $description = $description->getText();
                ?>
                <li class="item-list__item flag mb-">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <?php echo $item->status ? _('Enabled') : _('Disabled') ?>
                            </div>
                            <div class="media__body item-list__title">
                                <?php
                                echo \Story\HTML::link(
                                    $url,
                                    h($item->slug)
                                ) ?>
                            </div>
                        </div>
                        <div class="item-list__description">
                            <?php echo $description ? $description : '<em>'._('There are no notes.').'</em>' ?>
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
