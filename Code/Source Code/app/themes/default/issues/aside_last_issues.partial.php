<?php
if (count($issues)) { ?>
    <?php
        foreach ($issues as $issue) {
            ?>

            <!-- .featured-issue -->
            <div class="featured-issue u-mb">
                <div class="featured-issue__title">
                    <?php echo $issue->title ?>
                </div>
                <div class="featured-issue__body">
                    <a href="<?php echo action('\Project\Controllers\Issues\Index', array($issue->slug)) ?>">
                        <img src="<?php echo issue_cover_page_url($issue) ?>" alt="" class="featured-issue__img">

                    </a>
                    <?php
                    echo has_access('issues_order') && $issue->inventory ? link_to(
                        action('\Project\Controllers\Issues\Order', array($issue->slug)),
                        _('Order Now'),
                        array('class' => 'btn btn--alert')
                    ) : ''?>
                </div>
            </div><!-- /.featured-issue -->
    <?php
        } ?>

<?php } ?>
