<div class="notifications">
    <ol>
<?php
/** @var Story\Session $session */
$session = $app['session'];

$haveNotification = $session->get('error') || $session->get('notice') || count($errors);
if ($haveNotification) { ?>


<?php
}



if ($error = $session->get('error')) { ?>

            <li class="notification notification--negative">
                <div class="flag">
                    <div class="flag__img i-<?php echo $session->get('notifSymbol') ?: 'exclamation-circle' ?>">&nbsp;</div>
                    <div class="flag__body">
                        <span class="notification__title"><?php echo $session->get('errorTitle') ?: _('Error') ?></span>
                        <span class="notification__content"><?php echo $error ?></span>
                    </div>
                </div>
            </li>

    <?php

} elseif ($notice = $session->get('notice')) { ?>

            <li class="notification notification--positive">
                <div class="flag">
                    <div class="flag__img i-<?php echo $session->get('notifSymbol') ?: 'info-circle' ?>">&nbsp;</div>
                    <div class="flag__body">
                        <span class="notification__title"><?php echo $session->get('noticeTitle') ?: _('Success') ?></span>
                        <span class="notification__content"><?php echo $notice ?></span>
                    </div>
                </div>
            </li>

    <?php
}
?>
    </ol>
</div>
