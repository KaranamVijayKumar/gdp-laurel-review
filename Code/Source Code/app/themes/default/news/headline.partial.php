    <h2 class="archive__heading u-mt0">
        <?php echo link_to(action('\Project\Controllers\News\Show', array($article->slug)), h($title)) ?>
    </h2>
    <aside>
        <?php $created = diff_humans($article->created); ?>
        Created <span title="<?php echo $created[0] ?>"><?php echo $created[1] ?>.</span>
    </aside>
    <hr/>
    <div class="archive__headline">
        <?php echo $content ?>
    </div>
