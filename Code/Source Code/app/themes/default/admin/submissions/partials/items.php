<div id="results">
    <?php if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d submission.', '%d submissions.', $total), $total) ?></em></p>
        <ol class="item-list pt">
            <?php foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\Submissions\Show', array($item->id));
//                $created = \Carbon\Carbon::createFromTimestamp($item->created);
                $author = '';
                if (has_access('admin_submissions_view_author')) {
                    $author = '<em>by '.\Story\HTML::link(action('\Project\Controllers\Admin\Submissions\Index', array($selectedStatus, $selectedCategory)) . '?'.http_build_query(array('q' => $item->author_email)), $item->author_name ?: $item->author_email).'</em><br>';
                }
                ?>
                <li class="item-list__item flag mb-">

                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <span title="<?php echo $item->created ?>"><?php printf(_('Created %s'), $item->created->diffForHumans()) ?></span>

                            </div>
                            <div class="media__body item-list__title">

                                <?php echo \Story\HTML::link($url, h($item->name)) ?>
                            </div>
                        </div>
                        <div class="item-list__description">

                            <?php echo $author ?>
                            <?php if (isset($item->likes) && $item->likes) { ?>
                                <small class="i-thumbs-o-up pr-- green" title="<?php echo _('Likes') ?>"><?php echo $item->likes ?></small>
                            <?php } ?>
                            <?php if (isset($item->dislikes) && $item->dislikes) { ?>
                                <small class="i-thumbs-o-down pr-- red" title="<?php echo _('Dislikes') ?>"><?php echo $item->dislikes ?></small>
                            <?php } ?>
                            <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Submissions\Index', array($item->status_slug, $selectedCategory)), $item->status_name, array('class' => 'btn btn--tiny btn--secondary')) ?>
                            <?php echo $item->category_slug ? \Story\HTML::link(action('\Project\Controllers\Admin\Submissions\Index', array($selectedStatus, $item->category_slug)), $item->category_name, array('class' => 'btn btn--tiny btn--secondary')) : '' ?>


                        </div>
                    </div>
                </li>
            <?php } ?>

        </ol>

        <div class="c"><?php echo $pagination ?></div>
        <?php require_once __DIR__ . '/../export.partial.php' ?>
    <?php } ?>
</div>
