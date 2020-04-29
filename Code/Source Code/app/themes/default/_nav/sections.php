<?php
/*!
 * global.section.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2014 Arpad Olasz
 * All rights reserved.
 *
 */

/** @var \StoryEngine\StoryEngine $engine */

/*
|--------------------------------------------------------------------------
| Account navigation
|--------------------------------------------------------------------------
|
| The visitor's account navigation
|
*/

$engine->section(
    'nav--account',
    function ($section) {

        /** @var  \StoryEngine\Interfaces\SectionInterface $section */
        $section->setCache(array(false, 0)); // we do not cache this section

        $section->setTemplate('_nav/account');

        $accountItems = array();


        if (has_access('cart_index')) {
            /** @var \StoryCart\Cart $cart */
            $cart = app('cart');
            $count = count($cart->all());
            $accountItems['cart'] =\Story\HTML::link(
                action('\Project\Controllers\Cart\Index'),
                '<i class="icon-basket"></i>' . _('Cart') . ' (<strong class="text--alert">'. $count . '</strong>)',
                array('class' => (!$count ? 'text--secondary' : 'text--alert'))
            );
        }

        // sign in
        if (\Story\Auth::check()) {

            if (has_access('account_dashboard')) {
                $accountItems['account'] =

                    \Story\HTML::link(
                        action('\Project\Controllers\Account\Dashboard'),
                        '<i class="icon-profile"></i>' . _('Account')
                    );
            }

            // if admin, we include
            if (has_access('admin_dashboard')) {
                $accountItems['admin'] = \Story\HTML::link(
                    action('\Project\Controllers\Admin\Dashboard'),
                    '<i class="icon-layers"></i>' . _('Admin')
                );
            }

            $accountItems['signout'] = \Story\HTML::link(
                action('\Project\Controllers\Logout'),
                _('Sign Out'),
                array('class' => 'text--alert')
            );
        } else {
            $accountItems['signin'] =

                \Story\HTML::link(
                    action('\Project\Controllers\Auth'),
                    '<i class="icon-profile"></i>' . _('Sign In')
                );
        }

        $data = array(
            'account_menus' => $accountItems
        );

        $section->setData($data);

    }
);

/*
|--------------------------------------------------------------------------
| Footer menu
|--------------------------------------------------------------------------
|
| The site's footer menu
|
*/

$engine->section(
    'nav-footer',
    function ($section) {

        /** @var  \StoryEngine\Interfaces\SectionInterface $section */

        // we cache the menu for 10 minutes with the file cacher
        $section->setCache(array('file', 600)); // cache for 10 minutes
        $section->getCache()->setOptions(array('cache_path' => SP . '/storage/cache'));

        // set the template
        $section->setTemplate('_nav/footer');

        // set the data
        $section->setData(
            array(
                'menu' => \Project\Support\MenuFactory::get(
                    'nav-footer',
                    isset($selected) ? $selected : array(),
                    array('class' => 'nav text--right u-mb')
                )
            )
        );

    }
);
