<div id="results" class="mb">
    <?php
    if (!count($items)) { ?>
        <p class="note mb0"><em><?php echo _('No results.') ?></em></p>
        <hr/>
    <?php
    } else { ?>
        <p class="note mb0">
            <em><?php echo sprintf(ngettext('%d submission found.', '%d submissions found.', $total), $total) ?></em>
        </p>
        <hr/>
        <ol class="items">
            <?php
                foreach ($items as $item) { ?>
                <?php
                    $url =  action('\Project\Controllers\Submissions\Show', array($item->id));
                    ?>
                    <li class="item">
                        <div class="item__img">
                            <?php
                            echo \Story\HTML::link(
                                action(
                                    '\Project\Controllers\Submissions\Index',
                                    array($item->status_slug, $selectedCategory)
                                ),
                                $item->status_name,
                                array('class' => 'pill pill--small')
                            ) ?>
                            <?php
                            echo $item->category_slug ?
                                \Story\HTML::link(
                                    action(
                                        '\Project\Controllers\Submissions\Index',
                                        array($selectedStatus, $item->category_slug)
                                    ),
                                    $item->category_name,
                                    array('class' => 'pill pill--small')
                                ) : '' ?>

                        </div>
                        <div class="item__body">
                            <?php echo \Story\HTML::link($url, h($item->name), array('class' => 'item__title')) ?>
                            <div class="item__description" title="<?php echo $item->created ?>">
                                <?php printf(_('Created %s'), $item->created->diffForHumans()) ?>
                            </div>
                        </div>
                    </li>
            <?php
                } ?>

        </ol>
        <div class="mv">
            <?php echo $pagination ?>
        </div>
    <?php
    } ?>
</div>
