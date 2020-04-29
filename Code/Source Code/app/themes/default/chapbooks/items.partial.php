<?php
if (!count($chapbooks)) { ?>

    <div class="notifications">
        <ol>
            <li class="notification">
                <div class="notification__img icon-sad"></div>
                <div class="notification__body">
                    <?php echo _('Currently there are no chapbooks.') ?>
                </div>

            </li>
        </ol>
    </div>

    <?php
} else { ?>

        <!-- .grid-list -->
        <div class="grid-list mt0">
            <!--
            <?php
                foreach ($chapbooks as $item) { ?>
                    <?php
                        $img_url = \Project\Models\ChapbookFile::createCoverPageImageUrl($item->storage_name);
                        $chapbook_url = action('\Project\Controllers\Chapbooks\Index', array($item->slug));
                    ?>
                    --><div class="grid-list__item u-1-of-5 u-1-of-2-palm text--center">
                        <a href="<?php echo $chapbook_url ?>">
                            <img alt="<?php echo $item->title ?>" src="<?php echo $img_url ?>" class="grid-list__img">
                        </a>
                        <div class="grid-list__body">
                            <?php echo \Story\HTML::link($chapbook_url, $item->title) ?>
                        </div>
                        <?php
                                if (has_access('chapbooks_order')) { ?>
                                    <div class="grid-list__footer">
                                        <?php echo get_chapbook_price($item, $chapbook) ?: _('Out of Stock'); ?>
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
                <div class="mv">
                    <?php echo $pagination ?>
                </div>
        <?php
            } ?>
    <?php
}
