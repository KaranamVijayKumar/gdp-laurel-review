<div id="results">
    <?php
    if (!count($items)) { ?>
        <p class="mb-"><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d exporter.', '%d exporters.', $total), $total) ?></em></p>
        <ol class="item-list  pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url = action('\Project\Controllers\Admin\Exporters\Edit', array($item->id));
                ?>
                <li class="item-list__item flag">

                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <span title="<?php echo $item->created ?>">
                                    <?php printf(_('Created %s'), $item->created->diffForHumans()) ?>
                                </span>,
                                <?php echo $item->status ? _('Enabled') : _('Disabled') ?>
                            </div>
                            <div class="media__body">
                                <?php echo \Story\HTML::link($url, $item->name, array('class' => 'item-list__title')) ?>
                                <div class="item-list__description">
                                <?php
                                    echo $item->description ?
                                        ellipsize($item->description, 300) :
                                        '<em>' . _('Description not available.') . '</em>' ?>
                                </div>
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
