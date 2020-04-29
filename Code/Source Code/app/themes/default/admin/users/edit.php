<?php
/*!
 * edit.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */


// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
    <h4 class=""><?php echo _('Services') ?></h4>
    <ul class="nav nav--stacked delta">
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action(
                    '\Project\Controllers\Admin\Submissions\Index',
                    array(
                        \Project\Models\SubmissionStatus::ALL,
                        \Project\Models\SubmissionCategory::ALL
                    )
                ) . '?' . http_build_query(array('q' => $user->email)),
                _('Submissions'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action(
                    '\Project\Controllers\Admin\Subscriptions\Index',
                    array(
                        \Project\Models\Subscription::ALL,
                        \Project\Models\Subscription::ALL
                    )
                ) . '?' . http_build_query(array('q' => $user->email)),
                _('Subscriptions'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
    </ul>
    <h4 class=""><?php echo _('Edit account') ?></h4>
    <p><?php echo _("Choose the user's properties from the list below to edit them.") ?></p>
    <ul class="nav nav--stacked delta">
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Biography', array($user->id)),
                _('Biography'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
        <li>&nbsp;</li>
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Email', array($user->id)),
                _('Name and Email address'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Password', array($user->id)),
                _('Password'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Contact', array($user->id)),
                _('Contact and Shipping Address'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Roles', array($user->id)),
                _('Roles'),
                array('class' => 'i-chevron-right i--delta')
            ) ?>
        </li>
    </ul>

    <h4 class=""><?php echo _('Delete account') ?></h4>
    <p><?php echo _('Use the link below to delete the user permanently.') ?></p>
    <ul class="nav nav--stacked delta">
        <li class="pv-">
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Delete', array($user->id)),
                _('Delete account'),
                array('class' => 'i-chevron-right i--delta red')
            ) ?>
        </li>
    </ul>
    <h4 class=""><?php echo _('Info') ?></h4>
<?php
$last_login = $user->last_login ? \Carbon\Carbon::createFromTimestamp($user->last_login) : false;
$created = $user->created ? \Carbon\Carbon::createFromTimestamp($user->created) : false;
$modified = $user->modified ? \Carbon\Carbon::createFromTimestamp($user->modified) : false;
?>
    <table class="1/1 mb">
        <tbody>
        <tr>
            <td class="1/4"><?php echo _('Last Login') ?></td>
            <td><?php
                echo $last_login ? $last_login->diffForHumans() : _(
                    'Never'
                ) ?> <?php echo $last_login ? '(' . $last_login->toDayDateTimeString() . ')' : '' ?></td>
        </tr>
        <tr>
            <td class="1/4"><?php echo _('Status') ?></td>
            <td><?php echo $user->active ? _('Active') : _('Inactive') ?></td>
        </tr>
        <tr>
            <td class="1/4"><?php echo _('Created') ?></td>
            <td><?php
                echo $created ? $created->diffForHumans() : _(
                    'Unknown'
                ) ?> <?php echo $created ? '(' . $created->toDayDateTimeString() . ')' : '' ?></td>
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
        <div class="flag flag--small flag--rev">
            <div class="flag__img">
                <?php echo \Story\HTML::gravatar($user->email, 32, '', 'mm'); ?>
            </div>
            <div class="flag__body gamma pv-">
                <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Users\Index'), $title) ?>
                /
                <?php echo h($user->profiles->findBy('name', 'name')->value ?: $user->email) ?>
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
