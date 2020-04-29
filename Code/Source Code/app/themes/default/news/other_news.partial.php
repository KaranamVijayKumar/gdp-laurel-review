<?php if (count($news)) { ?>

    <h3 class="latest-news__title content-hero content-hero--secondary text--center">
        <?php echo _('Other news') ?>
    </h3>
    <div class="archive">
        <ol>
            <?php
                foreach ($news as $index => $item) { ?>
                    <?php $created = diff_humans($item->created); ?>
                    <li class="archive__<?php echo !$index ? 'report' : 'brief' ?> archive__report--responsive">
                        <article>
                            <h2 class="archive__heading">
                                <?php
                                echo link_to(
                                    action('\Project\Controllers\News\Show', array($item->slug)),
                                    h($item->headline->title)
                                ) ?>
                            </h2>
                            <aside title="<?php echo $created[0] ?>">
                                <?php echo $created[1] ?>
                            </aside>
                            <div class="text--secondary">
                                <?php echo $item->headline->content ?>
                            </div>

                        </article>
                    </li>

            <?php
                } ?>
        </ol>
    </div>
    <?php
} ?>
