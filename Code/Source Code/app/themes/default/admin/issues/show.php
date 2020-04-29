<?php
/*!
 * show.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

use Carbon\Carbon;
use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
    <div class="media mt">
        <?php
        /** @var stdClass $issue */
        if ($issue->cover_image) { ?>
            <div class="media__img 1/8 lap-1/5 palm-1/3 ml-">
                <a href="<?php echo $issue->cover_image->getCoverPageImageUrl() ?>">
                    <img src="<?php echo $issue->cover_image->getCoverPageImageUrl() ?>"
                         alt="<?php echo _('Cover page image') ?>" class=" generic-img palm-1/1"/>
                </a>
            </div>
        <?php
        } ?>
        <div class="media__body">
            <h1 class="mb0"><?php echo h($issue->title) ?></h1>
            <?php
            if ($issue->status) { ?>
                <p class="mb-">
                    <?php
                    echo HTML::link(
                        action('\Project\Controllers\Issues\Index', array($issue->slug)),
                        ' ' . _('View'),
                        array('class' => 'btn btn--tiny btn--secondary i-globe')
                    ) ?>
                </p>
            <?php
            } else { ?>
                <p class="gray mb-">
                    <span class="btn btn--tiny btn--secondary btn--disabled i-globe">
                        <?php echo _('View') ?>
                    </span>
                </p>

            <?php
            } ?>
            <p class="gray mv0 mr-">
                <?php
                echo $issue->description ? ellipsize($issue->description->content_text, 300) : '<em>' . _(
                    'Short description preview not available.'
                ) . '</em>' ?>
            </p>

        </div>
    </div>
    <ul class="nav nav--stacked delta">
        <?php
        if (has_access('admin_issues_toc')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Toc', array($issue->id)),
                    _('Table of Contents'),
                    array('class' => 'i-chevron-right i--delta')
                ) ?>
            </li>
        <?php
        } ?>

        <?php
        if (has_access('admin_issues_content')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Content', array($issue->id)),
                    _('Content'),
                    array('class' => 'i-chevron-right i--delta')
                ) ?>
            </li>
        <?php
        } ?>

        <?php
        if (has_access('admin_issues_edit')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Edit', array($issue->id)),
                    _('Properties'),
                    array('class' => 'i-chevron-right i--delta')
                ) ?>
            </li>
        <?php
        } ?>

        <li>&nbsp;</li>

        <?php
        if (has_access('admin_issues_delete')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Delete', array($issue->id)),
                    _('Delete issue'),
                    array('class' => 'i-chevron-right i--delta red')
                ) ?>
            </li>
        <?php
        } ?>

    </ul>
    <h4 class=""><?php echo _('Info') ?></h4>
<?php
$created = $issue->created ? Carbon::createFromTimestamp($issue->created) : false;
$modified = $issue->modified ? Carbon::createFromTimestamp($issue->modified) : false;
?>
    <table class="1/1 mb">
        <tbody>
            <tr>
                <td class="1/4"><?php echo _('Status') ?></td>
                <td><?php echo $issue->status ? _('Enabled') : _('Disabled') ?></td>
            </tr>
            <tr>
                <td class="1/4"><?php echo _('Created') ?></td>
                <td>
                    <?php
                    echo $created ? $created->diffForHumans() : _(
                        'Unknown'
                    ) ?>
                    <?php echo $created ? '(' . $created->toDayDateTimeString() . ')' : '' ?>
                </td>
            </tr>
            <tr>
                <td class="1/4"><?php echo _('Last modified') ?></td>
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
        <div class="flag flag--small flag--responsive flag--rev">
            <div class="flag__body gamma pv--">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Index'),
                    _('Issues')
                ) ?>
                /
                <?php echo h($issue->title) ?>
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

include __DIR__ . '/../_masters/page.master.php';
