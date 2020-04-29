<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors)) ?>
<?php
if ($mode === \Project\Models\SubmissionStatus::STATUS_ACCEPTED) { ?>
    <div class="mv">
        <?php require_once __DIR__ . '/partials/subscription_selector.partial.php' ?>
    </div>
<?php
} ?>
<h4 class="">
    <?php
    if ($mode === \Project\Models\SubmissionStatus::STATUS_ACCEPTED) { ?>

        <?php echo _('Acceptance email') ?>

    <?php
    } else { ?>

        <?php echo _('Refusal email') ?>

    <?php
    } ?>
</h4>

<?php require_once __DIR__ . '/partials/template_selector.partial.php' ?>
<p class="gray"><?php echo _('Please review the email that will be sent to the user:') ?></p>
<div class="layout ph-- mb">
    <div class="layout__item pb">
        <?php echo \Story\Form::label('subject', _('Subject')) ?>
        <?php
        echo \Story\Form::text(
            'subject',
            $dbTemplate->subject,
            array('class' => 'text-input 1/1 js-subject', 'id' => 'subject')
        ) ?>
    </div>
    <div class="layout__item">
        <?php echo \Story\Form::label('message', _('Message')) ?>
        <?php
        echo \Story\Form::textarea(
            'message',
            $dbTemplate->message,
            array(
                'rows'                    => '10',
                'class'                   => 'text-input 1/1 text-input--redactor js-message',
                'id'                      => 'message',
                'placeholder'             => _('Insert email message here ...')
            )
        ) ?>
    </div>

    <div class="cf"></div>
    <div class="layout__item 1/3 palm-1/1">
        <?php
        $label = $mode === \Project\Models\SubmissionStatus::STATUS_ACCEPTED ? _(
            'Send email and accept the submission'
        ) : _('Send email and decline the submission') ?>
        <?php
        echo \Story\Form::button(
            $label,
            array(
                'class' => 'confirm btn 1/1 ' .
                    ($mode === \Project\Models\SubmissionStatus::STATUS_ACCEPTED ? 'btn--positive' : 'btn--negative'),
                'type'  => 'submit'
            )
        ) ?>
    </div><!--
 --><div class="layout__item 2/3 palm-1/1 pt--">
        <?php
        echo \Story\HTML::link(
            action('\Project\Controllers\Admin\Submissions\Show', array($submission->id)),
            _('Cancel')
        ) ?>

    </div>
</div>
<?php echo \Story\Form::close() ?>

<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
$hide_tabs = true;
require_once __DIR__ . '/partials/toolbar.partial.php';

$global_toolbar = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
?>
<link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/redactor.css') ?>">
<link rel="stylesheet" href="<?php echo to('themes/default/admin/submissions/submissions.css') ?>">
<link rel="stylesheet" href="<?php echo to('themes/default/admin/chosen/style.css') ?>">
<link rel="stylesheet" href="<?php echo to('themes/default/admin/redactor/editor.css') ?>">


<?php

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
<script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/submissions/submissions.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/page.master.php';
