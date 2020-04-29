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
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<?php echo \Story\Form::open(array('errors' => $errors)) ?>

    <div class="layout">
        <div class="layout__item">
            <h4><?php echo _('Step 1. Page title and status') ?></h4>
        </div>
        <div class="layout__item 2/3 palm-1/1">
            <?php echo \Story\Form::label('title', _('Page Title')) ?>
            <?php
            echo \Story\Form::text(
                'title',
                '',
                array('class' => 'text-input 1/1', 'id' => 'title', 'placeholder' => _('A great page'))
            ) ?>
        </div>

        <div class="layout__item 1/3 palm-1/1 mt0">
            <?php echo \Story\Form::label('status', _('Status')) ?>
            <?php
            echo \Story\Form::select(
                'status',
                array('1' => _('Enabled'), '0' => _('Disabled')),
                '0',
                array('id' => 'status', 'class' => 'palm-1/1')
            ) ?>

        </div>
        <div class="layout__item mb">
            <h4><?php echo _('Step 2. Select a page type') ?></h4>

            <div class="js-show-details mh--">
                <?php
                $__fields = app('session')->get('__fields', array());
                ?>
                <div class="layout">
                    <div class="layout__item 1/3 palm-1/1 check-list">
                        <input name="page-type" type="radio" value="custom" id="page-type-custom"
                            class="js-show-details__selector" <?php
                            echo !isset($__fields['page-type']) || $__fields['page-type'] !== 'system' ?
                                'checked="checked"' : '' ?>>

                        <label for="page-type-custom"><?php echo _('Custom page') ?></label>
                    </div><!--
                 --><div class="layout__item 2/3 palm-1/1 check-list">
                        <input name="page-type" type="radio" value="system" id="page-type-system"
                           class="js-show-details__selector" <?php
                           echo isset($__fields['page-type']) && $__fields['page-type'] === 'system' ?
                               'checked="checked"' : '' ?>>
                        <label for="page-type-system"><?php echo _('Customize an existing system page') ?></label>
                    </div>
                </div>

                <!-- container -->
                <div class="js-show-details__container pt">

                    <div class="js-show-details__custom">
                        <?php
                        echo \Story\Form::label(
                            'slug',
                            _('Slug') .
                            ' <small class=additional>(' . _('Optional') .
                            ')</small>'
                        ) ?>
                        <?php
                        echo \Story\Form::text(
                            'slug',
                            '',
                            array(
                                'class'       => 'text-input 2/3 palm-1/1',
                                'id'          => 'slug',
                                'placeholder' => _('a-great-page')
                            )
                        ) ?>
                        <p class="gray">
                            <small>
                                <?php
                                echo _(
                                    'You can define a custom slug for the page. '.
                                    'If left blank the slug will be auto-generated from the title.'
                                ) ?>

                            </small>
                            <br/>
                            <small>
                                <?php
                                echo _(
                                    sprintf(
                                        'Example: If you enter <q class="green">a-great-page</q> as slug, '.
                                        'the page will be accessible at <q class="green">%sa-great-page</q>',
                                        \Story\URL::to('')
                                    )
                                ) ?>

                            </small>
                            <br/>
                            <small>
                                <?php
                                echo _(
                                    'When a system page is selected, leave the slug empty.'
                                ) ?>

                            </small>

                        </p>
                    </div>
                    <div class="js-show-details__system">
                        <?php
                        echo \Story\Form::label(
                            'system-slug',
                            _('Customize a system page')
                        ) ?>
                        <?php
                        echo \Story\Form::select(
                            'system-slug',
                            array('' => _('Select a system page')) + \Project\Models\Page::getSystemPages(),
                            '',
                            array(
                                'id'               => 'system-slug',
                                'class'            => 'chosen-select  2/3 palm-1/1',
                                'data-placeholder' => _('Select a system page')
                            )
                        ) ?>
                        <p class="gray">

                            <small>
                                <?php
                                echo _(
                                    'When customizing a system page all page interactions will be kept, only the ' .
                                    'default texts will be replaced with the custom content.'
                                ) ?>

                            </small>
                            <br/>
                            <small>
                                <?php
                                echo _(
                                    sprintf(
                                        'In order to customize the welcome page please select <q>%s</q> '.
                                        'from the system page list.',
                                        \Project\Models\Page::INDEX_PAGE_SLUG
                                    )
                                ) ?>

                            </small>

                        </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/4 lap-1/3 palm-1/1">
            <?php
            echo \Story\Form::button(
                _('Create and Continue'),
                array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
            ) ?>
        </div>
        <div class="layout__item" style="margin-bottom: 100px;">
            <p class="gray mb">
                <small>
                    <?php
                    echo _(
                        'Once the page is created you can enter the content.'
                    ) ?>

                </small>
            </p>
        </div>
    </div>


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
                    action('\Project\Controllers\Admin\Pages\Index'),
                    _('Pages')
                ) ?>
                /
                <?php echo _('New Page') ?>

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
<?php
$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
?>
    <script src="<?php echo to('themes/default/admin/vendor/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/redactor/redactor.min.js') ?>"></script>
    <script src="<?php echo to('themes/default/admin/chosen/chosen.jquery.min.js') ?>"></script>
<?php
$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ . '/../_masters/page.master.php';
