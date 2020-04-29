<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'summary.partial.php' ?>
<h4 class="mt0 mb- content-hero">

    <?php echo _('Author') ?>
    <?php echo count($order->items->getShippableItems()) ? ' / ' . _('Shipping Address') : '' ?>

</h4>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'user.partial.php' ?>
<h4 class="mt0 mb- content-hero"><?php echo _('Items') ?></h4>
<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'order_items.partial.php' ?>

