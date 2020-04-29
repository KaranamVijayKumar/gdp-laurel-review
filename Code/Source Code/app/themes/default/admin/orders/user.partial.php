<div class="layout">
    <div class="layout__item 1/2">
        <ul class="nav epsilon">
            <li class="1/1 pb-">
        <span class="label">
            <?php echo _('Name') ?>
        </span>
                <?php echo $order->order_user->findBy('name', 'name')->value ?>
            </li>
            <li class="1/1 pb-">
                <span class="label">
                    <?php echo _('Address') ?>
                </span>
                <?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'address.partial.php' ?>

            </li>
            <li class="1/1 pb-">
                <span class="label">
                    <?php echo _('Phone') ?>
                </span>
                <?php
                $phone = $order->order_user->findBy('name', 'phone')->value;
                ?>
                <a href="callto:<?php echo $phone ?>"><?php echo $phone ?></a>
            </li>
            <li class="1/1">
                <span class="label">
                    <?php echo _('E-mail') ?>
                </span>
                <?php
                $email = $order->order_user->findBy('name', 'email')->value;
                echo \Story\HTML::link('mailto:' . $email, $email) ?>
            </li>
        </ul>
    </div>
</div>

