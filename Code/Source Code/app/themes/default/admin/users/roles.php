<?php
/*!
 * roles.php v0.1
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
    <h4 class=""><?php echo _('Roles') ?></h4>
    <p><?php echo _('Select the roles for the user. This will affect their access throughout the whole site.') ?></p>

<?php if (count($roles)) { ?>

    <?php echo \Story\Form::open(array('class' => 'filter')) ?>
    <div class="layout ph-- mb">
        <div class="layout__item ">
            <ul class="check-list p0">
            <?php
                foreach ($roles as $id => $role) { ?>
                    <li>
                        <?php
                        echo \Story\Form::checkbox(
                            'roles[]',
                            $id,
                            in_array($id, $userRoles),
                            array('id' => 'role_' . $id)
                        ) ?>

                        <?php echo \Story\Form::label('role_' . $id, $role) ?>
                    </li>
                <?php
                } ?>
            </ul>
        </div>
        <div class="cf"></div>
        <div class="layout__item 1/5 palm-1/1 pt+">
            <?php echo \Story\Form::button(_('Update'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
        </div>
    </div>
    <?php echo \Story\Form::close() ?>

<?php
} else { ?>

    <p><em class="error"><?php echo _('There are no roles to assign. Make sure you create one!') ?></em></p>

<?php
}

$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
$user_subtitle = _('Roles');
require_once __DIR__ . '/toolbar.partial.php';
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
