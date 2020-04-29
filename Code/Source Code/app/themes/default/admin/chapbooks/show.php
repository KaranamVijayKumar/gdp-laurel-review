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
        /** @var stdClass $chapbook */
        if ($chapbook->cover_image) { ?>
            <div class="media__img 1/8 lap-1/5 palm-1/3 ml-">
                <a href="<?php echo $chapbook->cover_image->getCoverPageImageUrl() ?>">
                    <img src="<?php echo $chapbook->cover_image->getCoverPageImageUrl() ?>"
                         alt="<?php echo _('Cover page image') ?>" class=" generic-img palm-1/1"/>
                </a>
            </div>
        <?php
        } ?>
        <div class="media__body">
            <h1 class="mb0"><?php echo h($chapbook->title) ?></h1>
            <?php
            if ($chapbook->status) { ?>
                <p class="mb-">
                    <?php
                    echo HTML::link(
                        action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)),
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
                echo $chapbook->description ? ellipsize($chapbook->description->content_text, 300) : '<em>' . _(
                    'Short description preview not available.'
                ) . '</em>' ?>
            </p>

        </div>
    </div>
    <ul class="nav nav--stacked delta">
        <?php
        if (has_access('admin_chapbooks_toc')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Chapbooks\Toc', array($chapbook->id)),
                    _('Table of Contents'),
                    array('class' => 'i-chevron-right i--delta')
                ) ?>
            </li>
        <?php
        } ?>

        <?php
        if (has_access('admin_chapbooks_content')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Chapbooks\Content', array($chapbook->id)),
                    _('Content'),
                    array('class' => 'i-chevron-right i--delta')
                ) ?>
            </li>
        <?php
        } ?>

        <?php
        if (has_access('admin_chapbooks_edit')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Chapbooks\Edit', array($chapbook->id)),
                    _('Properties'),
                    array('class' => 'i-chevron-right i--delta')
                ) ?>
            </li>
        <?php
        } ?>

        <li>&nbsp;</li>

        <?php
        if (has_access('admin_chapbooks_delete')) { ?>
            <li class="pv-">
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Chapbooks\Delete', array($chapbook->id)),
                    _('Delete chapbook'),
                    array('class' => 'i-chevron-right i--delta red')
                ) ?>
            </li>
        <?php
        } ?>

    </ul>
    <h4 class=""><?php echo _('Info') ?></h4>
<?php
$created = $chapbook->created ? Carbon::createFromTimestamp($chapbook->created) : false;
$modified = $chapbook->modified ? Carbon::createFromTimestamp($chapbook->modified) : false;
?>
    <table class="1/1 mb">
        <tbody>
            <tr>
                <td class="1/4"><?php echo _('Status') ?></td>
                <td><?php echo $chapbook->status ? _('Enabled') : _('Disabled') ?></td>
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
                    action('\Project\Controllers\Admin\Chapbooks\Index'),
                    _('Chapbooks')
                ) ?>
                /
                <?php echo h($chapbook->title) ?>
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
