<?php if (count($order->logs)) { ?>
<?php
    foreach ($order->history as $history) { ?>
        <?php $created = \Carbon\Carbon::createFromTimestamp($history->created) ?>

        <?php if ($history instanceof \Project\Models\Log) { ?>

            <div class="media media--rev mv">
                <div class="media__img activity__created 1/6 tr">
                    <small class="gray" title="<?php echo $created ?>">
                        <?php echo $created->diffForHumans() ?>
                    </small>
                </div>
                <div class="media__body activity__msg--message i-chevron-right">
                    <?php echo $history->built_message ?>

                </div>
            </div>

        <?php } elseif ($history instanceof \Project\Models\Payment) { ?>

            <div class="media media--rev mv">
                <div class="media__img activity__created 1/6 tr">
                    <small class="gray" title="<?php echo $created ?>">
                        <?php echo $created->diffForHumans() ?>
                    </small>
                </div>
                <div class="media__body activity__msg--message i-chevron-right">

                    <?php echo $history->notes ?>

                    <?php if ($payment_data = $history->payment_data) { ?>

                        <a class="js-section-selector" href="#payment-<?php echo $history->id ?>">
                            <?php echo _('Details') ?>
                        </a>
                        <div class="hidden mv-" id="payment-<?php echo $history->id ?>">

                            <?php require __DIR__ . DIRECTORY_SEPARATOR . 'payment_details.partial.php' ?>

                        </div>
                    <?php } ?>
                </div>
            </div>

        <?php } ?>

        <?php
    } ?>

<?php } else { ?>
    <p>
        <em>
            <?php echo _('There are no history entries.') ?>
        </em>
    </p>
    <?php
}

