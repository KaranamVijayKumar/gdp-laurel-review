<?php
/*!
 * create.php v0.1
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
<?php
/** @var \Project\Models\Newsletter $newsletter */
if ($newsletter->sent) { ?>

    <?php $sent = \Carbon\Carbon::createFromTimestamp($newsletter->sent) ?>
    <p class="green content-hero i-check-circle">
        <?php
        echo sprintf(
            _(
                'This newsletter was sent %s (%s). You can find the overview below.'
            ),
            $sent->diffForHumans(),
            $sent
        ) ?>
    </p>
<?php
} ?>
<?php
if (!$newsletter->sent && $newsletter->status) { ?>
    <p class="orange content-hero i-exclamation-circle">
        <?php
        echo _(
            'This newsletter is in the send queue. You can still modify it, but might be sent out soon.' .
            ' Disable it to remove from the queue.'
        ) ?>
    </p>
<?php
} ?>
<?php echo Form::open(array('errors' => $errors)) ?>
    <div class="layout ph-- mv">
        <div class="layout__item 2/3 palm-1/1">
            <?php echo Form::label('subject', _('Subject')) ?>
            <?php
            echo $editable ? Form::text(
                'subject',
                $newsletter->content->first()->attributes['subject'],
                array('class' => 'text-input 1/1', 'id' => 'subject')
            ) : $newsletter->content->first()->attributes['subject'] ?>
        </div><!--
     --><div class="layout__item 1/3 palm-1/1">
            <?php echo Form::label('status', _('Status')) ?>
            <?php

            echo $editable ? Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                $newsletter->status,
                array('id' => 'status')
            ) : ($newsletter->status ? _('Enabled') : _('Disabled')) ?>
        </div>
        <div class="layout__item 1/1 pt">
            <?php echo Form::label('content', _('Content')) ?>
            <?php
            echo $editable ? Form::textarea(
                'content',
                $newsletter->content->first()->attributes['content'],
                array(
                    'rows'                     => '5',
                    'class'                    => 'text-input 1/1 text-input--redactor',
                    'id'                       => 'content',
                    'placeholder'              => _('Insert content here ...'),
                )
            ) : $newsletter->content->first()->attributes['content'] ?>
        </div>
        <div class="layout__item mb">
            <?php require_once __DIR__ .'/template_selector.partial.php' ?>
        </div>
        <div class="layout__item mb-">
            <?php
            echo Form::label('notes', _('Notes') . ' <small class="additional">(' . _('Optional') . ')</small>') ?>
            <?php
            echo $editable ? Form::text(
                'notes',
                '',
                array('class' => 'text-input 1/1', 'id' => 'notes')
            ) : $newsletter->notes ?>
        </div>
        <div class="cf"></div>
        <?php
        if ($editable) { ?>

            <div class="layout__item 1/5 palm-1/1">
                <?php echo Form::button(_('Save'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
            </div>
            <div class="layout__item">
                <p class="mb0 gray">
                    <?php
                    echo _(
                        'Newsletters are <strong class="orange">not</strong> sent out instantly, instead'.
                        ' they are put in a queue system which is processed on a daily basis.'
                    ) ?>
                </p>
                <p class="gray mv0">
                    <?php
                    echo _(
                        'Only enabled newsletters are sent out with the queue system.'
                    ) ?>
                </p>
            </div>

        <?php
        } ?>

    </div>
<?php echo Form::close() ?>
<?php echo Form::close() ?>
    <h4 class=""><?php echo _('Info') ?></h4>
<?php
$created = $newsletter->created ? Carbon::createFromTimestamp($newsletter->created) : false;
$modified = $newsletter->modified ? Carbon::createFromTimestamp($newsletter->modified) : false;
?>
    <table class="1/1 mb">
        <tbody>

        <tr>
            <td class="1/4"><?php echo _('Created') ?></td>
            <td>
                <?php
                echo $created ? $created->diffForHumans() : _(
                    '-'
                ) ?>
                <?php echo $created ? '(' . $created->toDayDateTimeString() . ')' : '' ?>
            </td>
        </tr>
        <tr>
            <td class="1/4"><?php echo _('Last Updated') ?></td>
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
                        if ($editable && has_access('delete_admin_news_newsletter_delete')) { ?>
                            <?php
                            echo Form::open(
                                array(
                                    'action' => action(
                                        '\Project\Controllers\Admin\News\Newsletter@delete',
                                        array($newsletter->id)
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
                        <?php echo HTML::link(action('\Project\Controllers\Admin\News\Newsletter'), _('Newsletter')) ?>
                        /
                        <?php echo $newsletter->content->first()->subject ?>
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
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/redactor.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">
    <link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/editor.css') ?>">

<?php

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <?php echo ws_redactor_assets('file', 'image', 'snippet', 'link'); ?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>

<?php

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
