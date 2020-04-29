<?php
/*!
 * index.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Story\Form;
use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<h4 class="content-hero"><?php echo _('Backup') ?></h4>
<p><?php echo _('Performing a backup only the database will be backed up and not the uploaded files.') ?></p>
<?php echo Form::open(array('class'=>'filter')) ?>

<?php echo Form::button(_('Backup now'), array('type'=>'submit', 'class'=>'btn')) ?>

<?php echo Form::close() ?>
<h4 class="content-hero"><?php echo _('Restore') ?></h4>

<?php // we have backups, spin through them and display each backup
if (count($backups)) { ?>
    <p class="gray"><em>
            <?php echo _("Click on the backup's name to download.") ?>
        </em></p>
    <ol class="item-list">
        <?php /** @var SplFileInfo $backup */
            foreach ($backups as $backup) { ?>
                <li class="item-list__item flag">
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <?php echo get_file_size($backup->getSize()) . ', ' .
                                    strftime('%c', $backup->getMTime())
                                ?>
                            </div>
                            <?php
                            echo HTML::link(
                                $backup->downloadLink,
                                $backup->displayName,
                                array('class'=>'item-list__title media__body')
                            )?>
                        </div>
                        <ul class="nav m0">
                            <li class="mr">
                                <a href="<?php
                                echo $backup->restoreLink ?>" class="green i-hdd-o confirm" data-confirm="<?php
                                echo _("All existing data from the database will be replaced with the backup's contents! \n\nThis action cannot be undone.") ?>">
                                    <?php echo _('Restore') ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php
                                echo $backup->deleteLink ?>" class="red i-trash-o confirm" data-confirm="<?php
                                echo _('This action cannot be undone.') ?>">
                                    <?php echo _('Delete') ?>
                                </a>
                            </li>
                        </ul>

                    </div>
                </li>

        <?php
            } ?>
    </ol>
    <p class="orange">
        <span class="i--negative i-exclamation-circle"></span>
        <?php
        echo _("Restoring a backup, all existing data from the database will be replaced with the backup's contents!")
        ?>
    </p>
    <p class="red mb">
        <span class="i--negative i-exclamation-circle"></span>
        <?php
        echo _(
            "Restoring large backups (&gt; 3 MB) is not recommended in the browser.".
            "Use the terminal instead with the following command:"
        )?>
        <br><q><code>php story restore</code></q>
    </p>
    <?php } else { ?>
            <p>
                <em><?php echo _('There are no available backups to restore.') ?></em>
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
    <div class="flag flag--rev flag--small flag--responsive ">
        <div class="flag__body gamma pv--">
            <?php echo _('Backup/Restore') ?>
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
