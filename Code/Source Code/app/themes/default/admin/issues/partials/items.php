<div id="results">
    <?php if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d issue.', '%d issues.', $total), $total) ?></em></p>
        <ol class="item-list pt">
            <?php foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\Issues\Show', array($item->id));
                $created = \Carbon\Carbon::createFromTimestamp($item->created);
                $cover_page = $item->storage_name ? \Project\Models\IssueFile::createCoverPageImageUrl($item->storage_name) : false;
                ?>
                <li class="item-list__item flag mb-">
                    <div class="flag__img 1/8 lap-1/8 palm-1/5">
                        <?php if ($cover_page) { ?>
                            <a href="<?php echo $cover_page ?>">
                                <img src="<?php echo $cover_page ?>" alt="<?php echo _('Cover page image') ?>" class=" generic-img 1/1"/>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <span title="<?php echo $created ?>"><?php printf(_('Created %s'), $created->diffForHumans()) ?></span>,
                                <?php echo $item->status ? _('Enabled') : _('Disabled') ?>

                            </div>
                            <div class="media__body item-list__title">
                                <?php echo \Story\HTML::link($url, h($item->title ?: $item->slug)) ?>
                            </div>
                        </div>
                        <div class="item-list__description">
                            <?php echo $item->content_text ? ellipsize($item->content_text, 300) : '<em>' . _('Short description preview not available.') . '</em>' ?>
                            <br>

                            <?php echo $item->status ? \Story\HTML::link(action('\Project\Controllers\Issues\Index', array($item->slug)), ' ' . _('View'), array('class' => 'btn btn--tiny btn--secondary i-globe')) : '<span class="btn btn--tiny btn--secondary btn--disabled i-globe"> '._('View').'</span>' ?>

                        </div>
                    </div>
                </li>
            <?php } ?>

        </ol>

        <div class="c"><?php echo $pagination ?></div>

    <?php } ?>
</div>
