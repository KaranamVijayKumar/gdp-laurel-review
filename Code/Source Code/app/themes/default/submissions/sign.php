<?php
/*!
 * sign.php v0.1
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
<div class="flag">
    <div class="flag__img">
        <span class="icon-upload icon--huge"></span>
    </div>
    <div class="flag__body">
        <h2 class="u-mv0"><q><?php echo h($submission->name) ?></q></h2>
        <p class="note u-mt0">
            Sign the submission.
        </p>
    </div>
</div>
<hr/>
<h3><?php echo $sign_template->subject ?></h3>
<?php require __DIR__ . '/../_global/notifications.php'; ?>

<?php echo Form::open(array('errors' => $errors)) ?>

<div>
    <?php echo $sign_template->message ?>
</div>

<?php echo Form::open(array('errors' => $errors)) ?>

<ul class="check-list nav u-mb">
    <li>
        <?php echo Form::checkbox('agree', 'yes', false, array('id' => 'agree')) ?>
        <?php
        echo Form::label(
            'agree',
            sprintf(_('I agree with the %s'), HTML::link('/terms', _('Terms &amp; Conditions')))
        ) ?>

    </li>
</ul>
<p>
    <?php echo Form::button(_('Sign the submission'), array('type' => 'submit', 'class' => 'btn u-1-of-1-palm')) ?>
</p>

<?php echo Form::close() ?>
    <hr/>
<p>
    <?php
    echo HTML::link(
        action('\Project\Controllers\Submissions\Show', array($submission->id)),
        _('Back to the submission')
    ) ?>
</p>
<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
include "create_promo.partial.php";

$global_content_aside = ob_get_clean();

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
// $breadcrumbs
// --------------------------------------------------------------
$breadcrumbs = array(
    HTML::link('', _('Home')),
    HTML::link(action('\Project\Controllers\Account\Dashboard'), _('Account')),
    HTML::link(action('\Project\Controllers\Submissions\Index'), _('My Submissions')),
    HTML::link(
        action('\Project\Controllers\Submissions\Show', array($submission->id)),
        html2text($submission->name)
    )
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
