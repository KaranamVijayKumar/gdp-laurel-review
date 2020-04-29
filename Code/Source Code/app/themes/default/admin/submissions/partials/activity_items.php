<?php if (count($items)) { ?>
    <?php
        foreach ($items as $index => $item) { ?>
            <div class="media media--rev pb--">
                <div class="media__img activity__created 1/8">
                    <small class="silver" title="<?php echo $item->created ?>">
                        <?php echo $item->human_created ?>
                    </small>
                </div>
                <div class="media__body activity__msg--<?php echo $item->type ?>">
                    <?php echo $item->msg ?>

                </div>
            </div>

        <?php
        } ?>

<?php } else { ?>
        <p>
            <em>
                <?php echo _('There is no activity.') ?>
            </em>
        </p>
<?php
}

