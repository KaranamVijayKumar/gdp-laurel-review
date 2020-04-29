<?php
/** @var Story\Session $session */
$session = $app['session'];

$haveNotification = $session->get('error') || $session->get('notice') || count($errors);

if ($haveNotification) { ?>

    <div class="notifications">
    <ol>

<?php
}



if ($error = $session->get('error')) { ?>

    <li class="notification notification--negative">
        <div class="notification__img icon-<?php echo $session->get('notifSymbol') ?: 'sad' ?>"></div>
        <div class="notification__body">
            <?php echo $session->get('errorTitle') ? '<h6 class="notification__header">' . $session->get('errorTitle') .'</h6>': '' ?>
            <?php echo $error ?>
        </div>
    </li>

<?php

} elseif ($notice = $session->get('notice')) { ?>

    <li class="notification notification--positive">
        <div class="notification__img icon-<?php echo $session->get('notifSymbol') ?: 'happy' ?>"></div>
        <div class="notification__body">
            <?php echo $notice ?>
        </div>
    </li>

<?php
}

if ($haveNotification) { ?>

    </ol>
    </div>

<?php
}
