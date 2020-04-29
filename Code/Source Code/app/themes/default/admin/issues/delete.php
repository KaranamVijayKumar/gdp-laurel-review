<?php
/*!
 * delete.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


use Story\HTML;

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------

ob_start();
?>
<div class="media media--rev">
    <?php
    if ($issue->cover_image) { ?>
        <div class="media__img 1/8 lap-1/5 palm-1/3 m">
            <a href="<?php echo $issue->cover_image->getCoverPageImageUrl() ?>">
                <img src="<?php echo $issue->cover_image->getCoverPageImageUrl() ?>"
                     alt="<?php echo _('Cover page image') ?>" class=" generic-img palm-1/1"/>
            </a>
        </div>
    <?php
    } ?>
    <div class="media__body">
        <h4><?php echo _('Delete Issue') ?></h4>
        <p>
            <?php
            echo sprintf(
                _(
                    "This action will permanently remove the <q>%s</q> issue!".
                    "This action is instant and cannot be recovered."
                ),
                '<strong>' . h($issue->title) . '</strong>'
            ) ?>
        </p>
        <?php echo \Story\Form::open(array('class' => 'filter')) ?>
            <div class="layout ph-- mb pt+">
                <div class="layout__item 1/4 lap-1/3 palm-1/1">
                    <?php
                    echo \Story\Form::button(
                        _('Delete Issue'),
                        array('class' => 'btn 1/1 btn--negative confirm', 'type' => 'submit')
                    ) ?>
                </div>
                <div class="layout__item pt">
                    <?php
                    echo HTML::link(
                        action('\Project\Controllers\Admin\Issues\Show', array($issue->id)),
                        ' ' . sprintf(_('Back to <q>%s</q>'), $issue->title),
                        array('class' => 'i-angle-double-left')
                    ) ?>
                </div>
            </div>
        <?php echo \Story\Form::close() ?>
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
        <div class="flag flag--small flag--rev">
            <div class="flag__body gamma pv--">
                <?php echo HTML::link(action('\Project\Controllers\Admin\Issues\Index'), _('Issues')) ?>
                /
                <?php
                echo HTML::link(
                    action('\Project\Controllers\Admin\Issues\Show', array($issue->id)),
                    h($issue->title)
                ) ?>

                /
                <span class="red"><?php echo _('Delete') ?></span>
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
