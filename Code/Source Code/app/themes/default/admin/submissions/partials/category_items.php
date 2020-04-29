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
                $url = action('\Project\Controllers\Admin\Submissions\Categories@edit', array($item->id));
                $name = h($item->name);
                $created = \Carbon\Carbon::createFromTimestamp($item->created);
                $count = isset($item->submissionCount) ? (int)$item->submissionCount : 0;
                $submissionsUrl = $count ? \Story\HTML::link(
                    action('\Project\Controllers\Admin\Submissions\Index', array('all', $item->slug)),
                    $count
                ) : _('No');
                $textGuidelines = ellipsize($item->guidelines_text, 400);
                ?>
                <li class="item-list__item flag">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <?php echo money_format('%n', $item->amount) ?>,
                                <?php
                                echo sprintf(
                                    ngettext('%s submission', '%s submissions', $count),
                                    $submissionsUrl
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
                            echo $textGuidelines ? $textGuidelines : '<em>' . _(
                                'No guidelines available.'
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
