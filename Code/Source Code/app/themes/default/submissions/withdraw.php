<?php
/*!
 * withdraw.php v0.1
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
            Withdraw the submission.
        </p>
    </div>
</div>
<hr/>
<h3><?php echo _('Withdraw your submission') ?></h3>

<?php require __DIR__ . '/../_global/notifications.php'; ?>

<?php echo Form::open(array('errors' => $errors)) ?>

    <p>
        <?php echo Form::label('category', _('Withdrawal type')) ?>

        <?php
        echo Form::select(
            'withdrawal_type',
            array(
                '0' => 'Select a whithdrawal type',
                'partial' => 'Partial withdrawal',
                'entire' => 'Withdraw entire submission'
            ),
            '0',
            array('class' =>'js-submission-details-selector', 'id' => 'withdrawal_type')
        ) ?>
    </p>

    <div class="js-submission-details">
        <div class="js-submission--0">
            <p class="pv--">
                <?php echo _('To get started, please select a withdrawal type from above.') ?>
            </p>
        </div>
        <div class="js-submission--partial">
            <div class="notifications">
                <ol>
                    <li class="notification notification--alert">
                        <div class="notification__img icon-caution"></div>
                        <div class="notification__body">
                            <?php
                            echo sprintf(
                                _(
                                    'You are about to withdraw '.
                                    '<span class="text--negative">part</span> of the <q>%s</q> submission.'
                                ),
                                $submission->name
                            ); ?>
                        </div>

                    </li>
                </ol>
            </div>
            <?php
            echo Form::label(
                'withdraw_comment',
                _('Enter the titles of the texts you wish to withdraw') .
                ' <span class="additional">('. _('Each title in a separate line') .')</span>'
            ) ?>

            <?php
            echo Form::textarea(
                'withdraw_comment',
                h($partial_withdrawn ? $partial_withdrawn->content : ''),
                array('class' => 'text-input u-1-of-1')
            ) ?>
            <ul class="check-list nav u-mb">
                <li>
                    <?php echo Form::checkbox('withdraw', 'yes', false, array('id' => 'withdraw_partial')) ?>
                    <?php echo Form::label('withdraw_partial', _('I am withdrawing the part of the  submission')) ?>

                </li>
            </ul>
            <p>
                <?php
                echo Form::button(
                    _('Withdraw part of the submission'),
                    array('type' => 'submit', 'class' => 'btn btn--negative confirm')
                ) ?>

            </p>
        </div>
        <div class="js-submission--entire">
            <div class="notifications">
                <ol>
                    <li class="notification notification--alert">
                        <div class="notification__img icon-caution"></div>
                        <div class="notification__body">
                            <?php
                            echo sprintf(
                                _(
                                    'You are about to withdraw the '.
                                    '<span class="text--negative">entire</span> <q>%s</q> submission.'
                                ),
                                $submission->name
                            ); ?>
                        </div>

                    </li>
                </ol>
            </div>
            <ul class="check-list nav u-mb">
                <li>
                    <?php echo Form::checkbox('withdraw', 'yes', false, array('id' => 'withdraw')) ?>
                    <?php echo Form::label('withdraw', _('I am withdrawing the entire submission')) ?>

                </li>
            </ul>
            <p>
                <?php
                echo Form::button(
                    _('Withdraw the submission'),
                    array('type' => 'submit', 'class' => 'btn btn--negative confirm')
                ) ?>

            </p>
        </div>
    </div>




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
