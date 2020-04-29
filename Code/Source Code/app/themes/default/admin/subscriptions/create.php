<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors, 'enctype' => 'multipart/form-data')) ?>

    <h4 class="content-hero">
        <?php echo _('Step 1. Choose a user') ?>
    </h4>

    <?php echo \Story\Form::label('user', _('Assign to user')) ?>
    <?php
    echo \Story\Form::select(
        'user',
        array('' => '') + $users,
        '',
        array(
            'id' => 'user',
            'class' => 'chosen-select  1/3 lap-1/1 palm-1/1',
            'data-placeholder' => _('Select a user...')
        )
    ) ?>
    <p class="gray">
        <?php echo _('Only users without an active subscription are shown.') ?>
    </p>

    <h4 class="content-hero">
        <?php echo _('Step 2. Pick a Subscription Category') ?>
    </h4>

    <?php echo \Story\Form::label('category', _('Category')) ?>
    <?php
        echo \Story\Form::select(
            'category',
            $categories,
            '',
            array('id' => 'category', 'class' => 'chosen-select  1/3 lap-1/1 palm-1/1')
        ) ?>

    <h4 class="content-hero"><?php echo _('Step 3. Set the Options &amp; Notes') ?></h4>
    <div class="layout ph-- mb">
        <div class="layout__item">
            <?php echo \Story\Form::label('status', _('Status')) ?>
            <?php
            echo \Story\Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                '1',
                array('id' => 'status', 'class' => 'chosen-select  1/3 lap-1/1 palm-1/1')
            ) ?>
        </div>
        <div class="layout__item mt">
            <?php
            echo \Story\Form::label(
                'description',
                _('Notes') . ' <small class=additional>(' . _('Optional') . ')</small>'
            ) ?>
            <?php
            echo \Story\Form::textarea(
                'description',
                '',
                array(
                    'rows'                    => '5',
                    'class'                   => 'text-input 1/1',
                    'id'                      => 'description',
                    'placeholder'             => _('You can add notes. Only admins will see it.'),
                )
            ) ?>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt">
            <?php
            echo \Story\Form::button(
                _('Create'),
                array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
            ) ?>
        </div>

    </div>
    <p class="gray">
        <?php
        echo _(
            'If the subscription is enabled, the user will receive a confirmation email.'
        ) ?>
    </p>
<?php echo \Story\Form::close() ?>
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
                <?php
                echo \Story\HTML::link(
                    action('\Project\Controllers\Admin\Subscriptions\Index'),
                    _('Subscription')
                ) ?>
                /
                <?php echo $title ?>
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

include __DIR__ .'/../_masters/page.master.php';
