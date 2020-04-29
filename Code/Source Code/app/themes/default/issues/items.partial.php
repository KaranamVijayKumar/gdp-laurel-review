<?php
if (!count($issues)) { ?>

    <div class="notifications">
        <ol>
            <li class="notification">
                <div class="notification__img icon-sad"></div>
                <div class="notification__body">
                    <?php echo _('Currently there are no issues.') ?>
                </div>

            </li>
        </ol>
    </div>

    <?php
} else { ?>

        <!-- .grid-list -->
        <div class="grid-list container">
            <!--
            <?php
                foreach ($issues as $item) { ?>
                    <?php
                        $img_url = \Project\Models\IssueFile::createCoverPageImageUrl($item->storage_name);
                        $issue_url = action('\Project\Controllers\Issues\Index', array($item->slug));
                    ?>
                    --><div class="grid-list__item u-1-of-5 u-1-of-2-palm text--center">
                        <a href="<?php echo $issue_url ?>">
                            <img alt="<?php echo $item->title ?>" src="<?php echo $img_url ?>" class="grid-list__img">
                        </a>
                        <div class="grid-list__body">
                            <?php echo \Story\HTML::link($issue_url, $item->title) ?>
                        </div>
                        <?php
                                if (has_access('issues_order')) { ?>
                                    <div class="grid-list__footer">
                                        <?php echo get_issue_price($item, $issue) ?: _('Out of Stock'); ?>
                                    </div>
                        <?php
                                } ?>
                    </div><!--

            <?php
                } ?>
            -->
        </div><!-- /.grid-list -->

        <?php
            if ($pagination) { ?>
                <div class="u-mv">
                    <?php echo $pagination ?>
                </div>
        <?php
            } ?>
    <?php
}
