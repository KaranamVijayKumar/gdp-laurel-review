<?php if ($total) { ?>

    <h1 class="mv0 content-hero content-hero--secondary"><?php echo _('Older News') ?></h1>
    <div class="archive">
        <ol>
            <?php
                foreach ($items as $item) { ?>
                    <?php $created = diff_humans($item->created); ?>
                    <li class="archive__brief">
                        <article>
                            <h2 class="archive__heading">
                                <?php
                                echo link_to(
                                    action('\Project\Controllers\News\Show', array($item->slug)),
                                    h($item->title)
                                ) ?>
                            </h2>
                            <aside title="<?php echo $created[0] ?>">
                                <?php echo $created[1] ?>
                            </aside>
                        </article>
                    </li>

            <?php
                } ?>
        </ol>
    </div>
    <hr/>
    <div class="u-mv">
        <?php echo $pagination ?>
    </div>
<?php
} ?>
