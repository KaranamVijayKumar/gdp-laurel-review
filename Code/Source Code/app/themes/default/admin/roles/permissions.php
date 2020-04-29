<div class="layout 1/1"><!--
<?php foreach ($permissions as $permission) { ?>
    <?php if (is_array($permission)) { ?>
 --><div class="layout__item 1/4 lap-1/3 palm-1/1 check-list">
                <?php echo $permission[0] ?>

                <?php echo $permission[1] ?>

    </div><!--
    <?php } else { ?>
 --><div class="layout__item 1/1">

            <?php echo $permission ?>
    </div><!--
    <?php } ?>
<?php } ?>
--></div>
