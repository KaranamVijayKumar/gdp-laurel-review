<?php
/*!
 * create.subscribers.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


use Carbon\Carbon;
use Story\Form;
use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<?php echo Form::open(array('errors' => $errors)) ?>
    <p>
        <?php
        echo _(
            "Update the subscriber's e-mail address."
        ) ?>
    </p>
    <div class="layout ph-- mv">
        <div class="layout__item 2/3 palm-1/1 mb">

            <?php echo Form::label('email', _('E-mail address')) ?>
            <?php
            echo Form::text(
                'email',
                $subscriber->email,
                array('class' => 'text-input 1/1', 'id' => 'email')
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1">
            <?php echo Form::button(_('Update'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
        </div>

    </div>
<?php echo Form::close() ?>
    <h4 class=""><?php echo _('Info') ?></h4>
<?php
$created = $subscriber->created ? Carbon::createFromTimestamp($subscriber->created) : false;
$modified = $subscriber->modified ? Carbon::createFromTimestamp($subscriber->modified) : false;
?>
    <table class="1/1 mb">
        <tbody>

        <tr>
            <td class="1/4"><?php echo _('Subscribed') ?></td>
            <td>
                <?php
                echo $created ? $created->diffForHumans() : _(
                    '-'
                ) ?>
                <?php echo $created ? '(' . $created->toDayDateTimeString() . ')' : '' ?>
            </td>
        </tr>
        <tr>
            <td class="1/4"><?php echo _('Updated') ?></td>
            <td><?php
                echo $modified ? $modified->diffForHumans(
                ) : '-' ?>
                <?php echo $modified ? '(' . $modified->toDayDateTimeString() . ')' : '' ?>
            </td>
        </tr>
        </tbody>
    </table>
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
                <div class="media media--rev">
                    <div class="media__img">
                        <?php
                        if (has_access('delete_admin_news_subscribers_delete')) { ?>
                            <?php
                            echo Form::open(
                                array(
                                    'action' => action(
                                        '\Project\Controllers\Admin\News\Subscribers@delete',
                                        array($subscriber->id)
                                    ),
                                    'method' => 'delete',
                                    'class'  => 'filter'
                                )
                            ) ?>
                            <button class="btn btn--negative confirm i-trash-o" name="action" value="trash"
                                    type="submit"> <?php echo _('Delete') ?></button>
                            <?php echo Form::close() ?>
                            <?php
                        } ?>
                    </div>
                    <div class="media__body">
                        <?php
                        echo HTML::link(action('\Project\Controllers\Admin\News\Subscribers'), _('Subscribers')) ?>
                        /
                        <?php echo $subscriber->email ?>
                    </div>
                </div>
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
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">

<?php

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>

<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
