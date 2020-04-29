<?php
/**
 * File: menu-main.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

use Story\HTML;

global $controller;

return array(
    /*
    |--------------------------------------------------------------------------
    | Site nav
    |--------------------------------------------------------------------------
    |
    | The site's main navigation
    |
     */
    'nav-site' => array(), // end site nav

    /*
    |--------------------------------------------------------------------------
    | Footer nav
    |--------------------------------------------------------------------------
    |
    | The site's footer navigation
    |
     */
    'nav-footer' => array(), // end site nav

    /*
    |--------------------------------------------------------------------------
    | Admin user menu
    |--------------------------------------------------------------------------
    |
    | Admin user menu
    |
     */

    'admin-menu-user' => array(
        // site
        array(
            'id' => 'site',
            'name' => HTML::link('/', '', array('class' => 'i-globe', 'title' => _('Visit web site'))),
        ),
        // dashboard
        array(
            'id' => 'dashboard',
            'name' => HTML::link(
                action('\Project\Controllers\Admin\Dashboard'),
                '',
                array('class' => 'i-home', 'title' => _('Administration Dashboard'))
            ),
            'access' => 'admin_dashboard',
        ),
        // account dashboard
        array(
            'id' => 'account_dashboard',
            'name' => HTML::link(
                action('\Project\Controllers\Admin\Account\Dashboard'),
                '',
                array('class' => 'i-user', 'title' => _('Account'))
            ),
            'access' => 'admin_account_dashboard',
        ),
        // help
        array(
            'id' => 'help',
            'name' => HTML::link(
                action(
                    '\Project\Controllers\Admin\Docs\Index',
                    array(
                        $controller instanceof \Project\Controllers\BaseController ?
                        substr($controller->route, mb_strlen(config('admin_path')) + 1) : '')
                ),
                '<span class="i-question yellow"></span>',
                array('class' => 'js-help', 'title' => _('Help and Documentation'))
            ),
            'access' => 'admin_docs_index',
        ),

        // logout
        array(
            'id' => 'logout',
            'name' => HTML::link(
                action('\Project\Controllers\Admin\Logout'),
                '',
                array('class' => 'i-power-off', 'title' => _('Sign Out'))
            ),
        ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Admin Main menu
    |--------------------------------------------------------------------------
    |
    | Admin main menu.
    |
     */

    'admin-menu-main' => array(

        // submissions
        array(
            'id' => 'submissions',
            'name' => HTML::link(action('\Project\Controllers\Admin\Submissions\Index'), _('Submissions')),
            'access' => 'admin_submissions_index',
            'docs' => 'submissions',
            // sub menus
            'children' => array(
                // chapbooks
                array(
                    'id' => 'submission_categories',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Submissions\Categories'), _('Categories')),
                    'access' => 'admin_submissions_categories',
                    'docs' => 'submisssions/categories',
                ),
            ),
        ),
        // subscriptions
        array(
            'id' => 'subscriptions',
            'name' => HTML::link(action('\Project\Controllers\Admin\Subscriptions\Index'), _('Subscriptions')),
            'access' => 'admin_subscriptions_index',
            'docs' => 'subscriptions',
            // sub menus
            'children' => array(
                // chapbooks
                array(
                    'id' => 'subscription_categories',
                    'docs' => 'subscriptions/categories',
                    'name' => HTML::link(
                        action('\Project\Controllers\Admin\Subscriptions\Categories'),
                        _('Categories')
                    ),
                    'access' => 'admin_subscriptions_categories',
                ),
            ),
        ),

        // news
        array(
            'id' => 'news',
            'name' => HTML::link(action('\Project\Controllers\Admin\News\Index'), _('News')),
            'access' => 'admin_news_index',
            'docs' => 'news',
            // sub menus
            'children' => array(
                // newsletter
                array(
                    'id' => 'newsletter',
                    'name' => HTML::link(action('\Project\Controllers\Admin\News\Newsletter'), _('Newsletter')),
                    'access' => 'admin_news_newsletter',
                    'docs' => 'newsletter',
                ),
                // subscribers
                array(
                    'id' => 'subscribers',
                    'name' => HTML::link(action('\Project\Controllers\Admin\News\Subscribers'), _('Subscribers')),
                    'access' => 'admin_news_subscribers',
                    'docs' => 'newsletter_subscribers',
                ),
            ),
        ),

        // issues
        array(
            'id' => 'issues',
            'name' => HTML::link(action('\Project\Controllers\Admin\Issues\Index'), _('Issues')),
            'access' => 'admin_issues_index',
            'docs' => 'issues',
            // sub menus
            'children' => array(
                // chapbooks
                array(
                    'id' => 'chapbooks',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Chapbooks\Index'), _('Chapbooks')),
                    'access' => 'admin_chapbooks_index',
                    'docs' => 'chapbooks',
                ),
            ),
        ),

        // pages
        array(
            'id' => 'pages',
            'name' => HTML::link(action('\Project\Controllers\Admin\Pages\Index'), _('Pages')),
            'access' => 'admin_pages_index',
            'docs' => 'pages',
            // sub menus
            'children' => array(
                // snippets
                array(
                    'id' => 'snippets',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Pages\Snippets'), _('Snippets')),
                    'access' => 'admin_pages_snippets',
                    'docs' => 'snippets',

                ),
                // menus
                array(
                    'id' => 'files',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Files\Index'), _('Files')),
                    'access' => 'admin_files_index',
                    'docs' => 'files',
                ),array(
                    'id' => 'aboutus',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Pages\Aboutus'), _('Aboutus')),
                    'access' => 'admin_pages_aboutus',
                    'docs' => 'aboutus',

                ),array(
                    'id' => 'podcast',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Pages\Podcast'), _('Podcast')),
                    'access' => 'admin_pages_podcast',
                    'docs' => 'podcast',

                ),
                // menus
                //                array(
                //                    'id'   => 'menus',
                //                    'name' => HTML::link(action('\Project\Controllers\Admin\Menus\Index'), _('Menus')),
                //                    'access' => 'admin_menus_index',
                //                )
            ),
        ),
        // files
        //        array(
        //            'id'   => 'files',
        //            'name' => HTML::link(action('\Project\Controllers\Admin\Files\Index'), _('Files')),
        //            'access' => 'admin_files_index',
        //        ),

        // orders
        array(
            'id' => 'orders',
            'name' => HTML::link(action('\Project\Controllers\Admin\Orders\Index'), _('Orders')),
            'access' => 'admin_orders_index',
            'docs' => 'orders',
        ),

        // preferences
        array(
            'id' => 'preferences',
            'name' => HTML::link(action('\Project\Controllers\Admin\Preferences\Index'), _('Preferences')),
            'access' => 'admin_preferences_index',
            'docs' => 'preferences',
            // sub menus
            'children' => array(
                // templates
                array(
                    'id' => 'templates',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Templates\Index'), _('Templates')),
                    'access' => 'admin_templates_index',
                    'docs' => 'templates',
                ),
                // users
                array(
                    'id' => 'users',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Users\Index'), _('Users')),
                    'access' => 'admin_users_index',
                    'docs' => 'users',
                ),
                // roles
                array(
                    'id' => 'roles',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Roles\Index'), _('Roles')),
                    'access' => 'admin_roles_index',
                    'docs' => 'roles',
                ),
                // exporters
                array(
                    'id' => 'exporters',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Exporters\Index'), _('Exporters')),
                    'access' => 'admin_exporters_index',
                    'docs' => 'exporters',
                ),
                // backup
                array(
                    'id' => 'backup',
                    'name' => HTML::link(action('\Project\Controllers\Admin\Backup\Index'), _('Backup/Restore')),
                    'access' => 'admin_backup_index',
                    'docs' => 'backup',
                ),
                // about
                array(
                    'id' => 'about',
                    'name' => HTML::link(action('\Project\Controllers\Admin\About'), _('About')),
                    'access' => 'admin_about',
                    'docs' => 'about',
                ),
            ),
        ),

    ), // end admin main menu
);
