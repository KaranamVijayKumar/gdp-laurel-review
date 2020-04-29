<?php
/*!
 * create.php v0.1
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
    <div class="layout ph-- mb">
        <div class="layout__item  1/1 mt">
            <!-- fileupload -->
            <div class="fileupload layout">
                <div class="layout__item 1/2">
                        <span class="btn btn--fileupload i-hdd-o">
                            <span><?php echo _('Choose Files &hellip;') ?></span>
                            <input class="js-fileupload__input" type="file" name="file" multiple>
                        </span>
                </div><!--
             --><div class="layout__item 1/2 tr">
                    <?php
                    echo Form::button(
                        ' ' . _('Start upload'),
                        array(
                            'id'       => 'files-upload-start',
                            'disabled' => 'disabled',
                            'class'    => 'i-upload btn btn--positive btn--disabled js-fileupload__start',
                            'type'     => 'button'
                        )
                    ) ?>
                </div>
                <div class="layout__item mv">
                    <div id="progress" class="fileupload__progress fileupload__progress--global js-fileupload__progress"
                         role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="fileupload__progress-bar fileupload__progress-bar--positive js-fileupload__progress-bar"></div>
                    </div>
                </div>
                <div class="layout__item">
                    <table class="table--striped table--borderless 1/1 fileupload__files js-fileupload__files">
                        <thead>
                            <tr>
                                <th colspan="3"><?php echo _('File upload queue:') ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="layout__item">
                    <h5 class="mb--"><?php echo _('Notes') ?></h5>
                    <p class="mv0 gray">
                        <small>
                            <?php
                            printf(
                                _('You can upload up to %2$s files, each not exceeding %1$s in size.'),
                                get_file_size(max_upload_size()),
                                '<strong>' . (int) config('per_page') .'</strong>'
                            ); ?>
                        </small>
                        <br/>
                        <small>
                            <?php
                            echo _(
                                'You can also drag &amp; drop files or directories '.
                                'in order to add them to the upload queue.'
                            ) ?>
                        </small>
                        <br/>
                        <small>
                            <?php echo _('Files that were successfully uploaded, will be removed from the queue.') ?>
                        </small>
                        <br/>
                        <small>
                            <?php
                            echo _(
                                'An unique prefix will be prepended to each file preserving the original file name. '.
                                'This is necessary to avoid overwritting existing files.'
                            ) ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
    <div class="flag__body">
        <div class="flag flag--small flag--responsive ">
            <div class="flag__body gamma pv--">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Files\Index'),
                    _('Files')
                ) ?>
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
?>
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/fileupload/fileupload.css') ?>">

<?php

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>

    <script>
        var storyAdmin = {
            uploadRemove: "<?php echo _('Remove') ?>",
            uploadAbort: "<?php echo _('Abort') ?>",
            uploadFailed: "<?php echo _('Failed') ?>",
            uploadSuccess: "<?php echo _('Done') ?>",
            uploadMaxFiles: <?php echo (int) config('per_page') ?>,
            uploadUrl: "<?php echo action('\Project\Controllers\Admin\Files\Create') ?>"
        }
    </script>
    <script src="<?php echo to('themes/default/admin/fileupload/fileupload.min.js') ?>"></script>
<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';

