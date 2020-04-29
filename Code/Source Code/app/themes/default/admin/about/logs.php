<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
use Story\HTML;

ob_start();
?>
<p><?php echo _("Select a log file to view it's contents.")?></p>
<?php
// we have logs, spin through them and display each link
if (count($logs)) { ?>
    <ol class="item-list item-list--nohide">
        <?php
        /** @var SplFileInfo $log */
            foreach ($logs as $log) { ?>
                <li class="item-list__item flag">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <?php
                            echo HTML::link(
                                $log->downloadLink,
                                $log->getBaseName(),
                                array('class'=>'item-list__title media__body')
                            )?>
                        </div>
                        <p class="item-list__description">
                            <?php
                            echo get_file_size($log->getSize()) . ', ' .
                                sprintf(
                                    _('Last modified: %s'),
                                    strftime('%c %Z', $log->getMTime())
                                ) ?>
                        </p>
                    </div>
                </li>
        <?php
            } ?>
    </ol>
    <?php } else { ?>
            <p>
                <em><?php echo _('There are no logs.') ?></em>
            </p>
<?php
}


$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
<div class="flag__body">
    <div class="flag flag--small flag--responsive ">
        <div class="flag__body gamma pv--">
            <?php echo HTML::link(action('\Project\Controllers\Admin\About'), _('About')) ?>
            /
            <?php echo $title ?>
        </div>
    </div>
</div>
<?php

$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
// extra head

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
 // extra footer

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
