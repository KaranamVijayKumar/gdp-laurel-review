<?php echo $order->order_user->findBy('name', 'address')->value ?>
    <br/>
    <?php echo $order->order_user->findBy('name', 'city')->value ?>

    <?php echo $order->order_user->findBy('name', 'state')->value ?>

    <?php echo $order->order_user->findBy('name', 'zip')->value ?>
    <br/>
    <?php echo $order->order_user->findBy('name', 'country')->value;
