<?php
/*!
 * create.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

// --------------------------------------------------------------
// Title
// --------------------------------------------------------------
if (!isset($title)) {
    $title = h($main_page_content->title);
}


// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<div class="text--user">
    <?php echo $engine->getSection('page-content') ?>
</div>
<?php

require __DIR__ . '/../_global/notifications.php';
echo Story\Form::open(array('errors' => $errors)) ?>

    <p class="u-pt0">
        <?php echo Story\Form::label('name', _('Name')) ?>

        <?php
        echo Story\Form::text(
            'name',
            '',
            array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'name', 'placeholder' => _('Enter your name'))
        ) ?>

        <?php echo Story\Form::label('email', _('E-mail address')) ?>

        <?php
        echo Story\Form::email(
            'email',
            '',
            array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'email', 'placeholder' => _('Enter your email'))
        ) ?>
    </p>
    <p class="u-pt-">
        <?php echo \Story\Form::label('password', _('Password')) ?>

        <?php
        echo \Story\Form::password(
            'password',
            array(
                'class' => 'text-input u-2-of-3 u-1-of-1-palm',
                'id' => 'password',
                'placeholder' => _('Min. 8 characters')
            )
        ) ?>


        <?php echo \Story\Form::label('verify_password', _('Verify Password')) ?>

        <?php
        echo \Story\Form::password(
            'verify_password',
            array('class' => 'text-input u-2-of-3 u-1-of-1-palm', 'id' => 'verify_password')
        ) ?>
    </p>
    <div class=" u-2-of-3 u-1-of-1-palm">
        <?php echo $sp->makeFields() ?>
    </div>
    <ul class="check-list nav">
        <li>
            <?php echo \Story\Form::checkbox('agree', 'yes', false, array('id' => 'agree')) ?>
            <?php
            echo \Story\Form::label(
                'agree',
                sprintf(_('I agree with the %s.'), \Story\HTML::link('/terms', _('Terms &amp; Conditions')))
            ) ?>

        </li>
    </ul>
    <p class="">
        <?php
        echo Story\Form::button(
            _('Create account'),
            array('class' => 'btn btn--positive 1/4 lap-u-2-of-3 u-1-of-1-palm u-mt-', 'type'=>'submit')
        ) ?>

    </p>
<?php echo Story\Form::close() ?>
<div class="text--user">
    <?php echo $engine->getSection('page-footer') ?>
</div>
<?php

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
?>
<div class="text--user">
    <?php echo $engine->getSection('page-aside') ?>
</div>
<p class="note text--center"><?php echo _('If you have an account you can also sign in.') ?></p>
<p class="text--center">
    <?php
    echo \Story\HTML::link(
        action('\Project\Controllers\Auth'),
        _('Sign In'),
        array('class' => 'btn u-1-of-1')
    ) ?>
</p>
<?php

echo $engine->getSection('page-side');

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
    \Story\HTML::link('', _('Home')),
    \Story\HTML::link(\Story\URL::current(), $title)
);

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
