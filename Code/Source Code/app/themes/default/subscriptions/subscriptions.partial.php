<div id="results" class="mb">
    <?php
    if (!count($subscriptions)) { ?>
        <p class="note u-mb0"><em><?php echo _('No results.') ?></em></p>
        <hr/>
    <?php
    } else { ?>
        <p class="note mb0">
            <em><?php
                echo sprintf(
                    ngettext('%d subscription found.', '%d subscriptions found.', count($subscriptions)),
                    count($subscriptions)
                ) ?></em>
        </p>
        <hr/>
        <ol class="items">
        <?php
            foreach ($subscriptions as $item) { ?>

                <li class="item">
                    <div class="item__img">
                        <?php echo sprintf(ngettext('%s Month', '%s Months', $item->interval), $item->interval) ?>

                    </div>
                    <div class="item__body">
                        <strong>
                        <?php echo $item->starts->toDayDateTimeString() ?>
                        -
                        <?php echo $item->expires->toDayDateTimeString() ?>
                        </strong>
                    </div>
                </li>
            <?php
            } ?>

        </ol>
        <div class="mv">
            <?php echo isset($pagination) ? $pagination : '' ?>
        </div>
    <?php
    } ?>
</div>
