<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0">
            <em><?php echo sprintf(ngettext('%d page.', '%d pages.', $total), $total) ?></em>
        </p>
        <ol class="item-list pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\Pages\Edit', array($item->id));
                $description = ellipsize($item->attributes['content_text'], 200);
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
                                    h($item->title ?: $item->slug)
                                ) ?>
                            </div>
                        </div>
                        <div class="item-list__description">
                            <?php
                            echo $item->status ?
                                \Story\HTML::link(
                                    \Story\URL::to($item->slug),
                                    ' ' . _('View'),
                                    array(
                                        'class' => 'btn btn--tiny btn--secondary i-globe',
                                        'target' => '_blank',
                                    )
                                ) : '<span class="btn btn--tiny btn--secondary btn--disabled i-globe" title="'
                                . _('Page is disabled') .'"> '._('View').
                                '</span>' ?>

                            <br/>
                            <?php echo $description ? $description : '<em>'._('Preview not available.').'</em>' ?>
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
