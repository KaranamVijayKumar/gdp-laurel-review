<div id="results">
    <?php
    if (!count($items)) { ?>
        <p class="mb0"><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d newsletter.', '%d newsletters.', $total), $total) ?></em></p>
        <ol class="item-list pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\News\Newsletter@edit', array($item->id));
                $created = \Carbon\Carbon::createFromTimestamp($item->created);
                $sent = \Carbon\Carbon::createFromTimestamp($item->sent);
                ?>
                <li class="item-list__item flag mb-">

                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <?php echo $item->status ? _('Enabled') : _('Disabled') ?>,
                        <?php
                                if ($item->sent) { ?>
                                    <span class="green" title="<?php echo $sent ?>">
                                        <?php printf(_('Sent %s.'), $sent->diffForHumans()) ?>
                                    </span>
                        <?php
                                } else { ?>

                                    <span class="orange"><?php echo _('Not yet sent.') ?></span>

                        <?php
                                } ?>
                            </div>
                            <div class="media__body item-list__title">
                                <?php echo \Story\HTML::link($url, h($item->subject)) ?>
                            </div>
                        </div>
                        <div class="item-list__description">
                            <?php echo $item->notes ?
                                '<strong>' . _('Notes') .'</strong>'. ': <em>' . $item->notes . '</em><br>' :
                                ''
                            ?>
                            <?php
                            echo $item->content_text ?
                                ellipsize($item->content_text, 300) :
                                '<em>' . _('Content preview not available.') . '</em>'
                            ?>
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
