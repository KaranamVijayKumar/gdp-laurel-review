<?php
/*!
 * index.php v0.1
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
<h4 class=""><?php echo _('Edit your account') ?></h4>
<p>
    <?php
    echo _(
        'You can make changes to your account at any time. '.
        'Change your email address, name or update your contact information.'
    ) ?>
</p>
<ul class="nav nav--stacked delta">
    <li class="pv-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Account\Biography'),
            _('Biography'),
            array('class'=>'i-chevron-right i--delta')
        ) ?>
    </li>
    <li>&nbsp;</li>
    <li class="pv-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Account\Email'),
            _('Name and Email address'),
            array('class'=>'i-chevron-right i--delta')
        ) ?>
    </li>
    <li class="pv-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Account\Password'),
            _('Password'),
            array('class'=>'i-chevron-right i--delta')
        ) ?>
    </li>
    <li class="pv-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Account\Contact'),
            _('Contact Information'),
            array('class'=>'i-chevron-right i--delta')
        ) ?>
    </li>
</ul>
<h4 class=""><?php echo _('Delete your account') ?></h4>
<p><?php echo _('You can also delete your account using the link below.')?></p>
<ul class="nav nav--stacked delta">
    <li class="pv-">
        <?php
        echo HTML::link(
            action('\Project\Controllers\Admin\Account\Delete'),
            _('Delete account'),
            array('class'=>'i-chevron-right i--delta red')
        ) ?>
    </li>
</ul>
<h4 class=""><?php echo _('Clear user data') ?></h4>
<p><?php echo _('Clearing the user data will remove the sort filters from all the administration modules.') ?></p>
<?php
echo HTML::link(
    action('\Project\Controllers\Admin\Account\Dashboard@clear'),
    _('Click here to clear user data'),
    array('class'=>'confirm')
) ?>

<p><?php echo $content ?></p>

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
            <?php echo HTML::gravatar($user->email, 32, '', 'mm'); ?>
        </div>
        <div class="flag__body gamma pv--">
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

include __DIR__ .'/../_masters/page.master.php';
