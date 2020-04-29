<?php
/**
 * File: routes.php
 * Created: 24-07-2014
 *
 * Based on MicroMVC
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

/**
 * URL Routing
 *
 * URLs are very important to the future usability of your site. Take
 * time to think about your structure in a way that is meaningful. Place
 * your most common page routes at the top for better performace.
 *
 * - Routes are matched from left-to-right.
 * - Regex can also be used to define routes if enclosed in "/.../"
 * - Each regex catch pattern (...) will be viewed as a parameter.
 * - The remaning (unmached) URL path will be passed as parameters.
 *
 ** Simple Example **
 * URL Path:    /forum/topic/view/45/Hello-World
 * Route:        "forum/topic/view" => 'Forum\Controller\Forum\View'
 * Result:        Forum\Controller\Forum\View->action('45', 'Hello-World');
 *
 ** Regex Example **
 * URL Path:    /John_Doe4/recent/comments/3
 * Route:        "/^(\w+)/recent/comments/' => 'Comments\Controller\Recent'
 * Result:        Comments\Controller\Recent->action($username = 'John_Doe4', $page = 3)
 */

global $app;

$ap = config('admin_path');

return array(
    // --------------------------------------------------------------
    // Listeners
    // --------------------------------------------------------------
    'paypal/ipn'                             => 'Project\Controllers\PaypalIpn',
    // --------------------------------------------------------------
    // Client routes
    // --------------------------------------------------------------
    ''                                       => '\Project\Controllers\IndexController',
    '404'                                    => '\Project\Controllers\NotFoundController',
    // shopping cart
    'cart/remove'                            => '\Project\Controllers\Cart\Remove',
    'cart/checkout'                          => '\Project\Controllers\Cart\Checkout',
    'cart/empty'                             => '\Project\Controllers\Cart\EmptyCart',
    'cart/thankyou'                          => '\Project\Controllers\Cart\Thankyou',
    'cart'                                   => '\Project\Controllers\Cart\Index',
    // login
    'account/forgot'                         => '\Project\Controllers\Account\Forgot',
    'account/reset'                          => '\Project\Controllers\Account\ResetPassword',
    'account/login'                          => '\Project\Controllers\Auth',
    'account/logout'                         => '\Project\Controllers\Logout',
    // account
    'account/create'                         => '\Project\Controllers\Account\Create',
    'account/activate'                       => '\Project\Controllers\Account\Activate',
    'account/contact'                        => '\Project\Controllers\Account\Contact',
    'account/biography'                      => '\Project\Controllers\Account\Biography',
    'account/delete'                         => '\Project\Controllers\Account\Delete',
    'account/email'                          => '\Project\Controllers\Account\Email',
    'account/password'                       => '\Project\Controllers\Account\Password',
    // force change password
    'account/changepassword'                 => '\Project\Controllers\Account\ChangePassword',
    // submissions
    'account/submissions/create'             => '\Project\Controllers\Submissions\Create',
    'account/submissions/checkout'           => '\Project\Controllers\Submissions\Checkout',
    'account/submissions/sign'               => '\Project\Controllers\Submissions\Sign',
    'account/submissions/withdraw'           => '\Project\Controllers\Submissions\Withdraw',
    'account/submissions/show'               => '\Project\Controllers\Submissions\Show',
    'account/submissions'                    => '\Project\Controllers\Submissions\Index',
    // subscriptions
    'account/subscriptions/create'           => '\Project\Controllers\Subscriptions\Create',
    'account/subscriptions/checkout'         => '\Project\Controllers\Subscriptions\Checkout',
    'account/subscriptions/cancel'           => '\Project\Controllers\Subscriptions\Cancel',
    'account/subscriptions/show'             => '\Project\Controllers\Subscriptions\Show',
    'account/subscriptions'                  => '\Project\Controllers\Subscriptions\Index',
    // account index
    'account'                                => '\Project\Controllers\Account\Dashboard',
    // newsletter + subscribe/unsubscribe
    'newsletter/subscribe'                   => '\Project\Controllers\Newsletter\Subscribe',
    'newsletter/unsubscribe'                 => '\Project\Controllers\Newsletter\Unsubscribe',
    'newsletter'                             => '\Project\Controllers\Newsletter\Index',
    // --------------------------------------------------------------
    // Issues
    // --------------------------------------------------------------
    'issues/order'                           => '\Project\Controllers\Issues\Order',
    'issues/checkout'                        => '\Project\Controllers\Issues\Checkout',
    'issues/toc'                             => '\Project\Controllers\Issues\TocContent',
    'issues'                                 => '\Project\Controllers\Issues\Index',
    // --------------------------------------------------------------
    // Chapbooks
    // --------------------------------------------------------------
    'chapbooks/order'                        => '\Project\Controllers\Chapbooks\Order',
    'chapbooks/checkout'                     => '\Project\Controllers\Chapbooks\Checkout',
    'chapbooks/toc'                          => '\Project\Controllers\Chapbooks\TocContent',
    'chapbooks'                              => '\Project\Controllers\Chapbooks\Index',
    // --------------------------------------------------------------
    // News
    // --------------------------------------------------------------
    'news/article'                           => '\Project\Controllers\News\Show',
    'news'                                   => '\Project\Controllers\News\Index',
    // --------------------------------------------------------------
    // Admin routes
    // --------------------------------------------------------------
    $ap . '/docs'                            => '\Project\Controllers\Admin\Docs\Index',
    // login
    $ap . '/account/forgot'                  => '\Project\Controllers\Admin\Account\Forgot',
    $ap . '/account/reset'                   => '\Project\Controllers\Admin\Account\ResetPassword',
    $ap . '/account/login'                   => '\Project\Controllers\Admin\Auth',
    $ap . '/account/logout'                  => '\Project\Controllers\Admin\Logout',
    // account
    $ap . '/account/biography'               => '\Project\Controllers\Admin\Account\Biography',
    $ap . '/account/contact'                 => '\Project\Controllers\Admin\Account\Contact',
    $ap . '/account/delete'                  => '\Project\Controllers\Admin\Account\Delete',
    $ap . '/account/email'                   => '\Project\Controllers\Admin\Account\Email',
    $ap . '/account/password'                => '\Project\Controllers\Admin\Account\Password',
    // account index
    $ap . '/account/clear'                   => '\Project\Controllers\Admin\Account\Dashboard@clear',
    // force change password
    $ap . '/account/changepassword'          => '\Project\Controllers\Admin\Account\ChangePassword',
    // account
    $ap . '/account'                         => '\Project\Controllers\Admin\Account\Dashboard',
    // submissions categories
    $ap . '/submissions/categories/create'   => '\Project\Controllers\Admin\Submissions\Categories@create',
    $ap . '/submissions/categories/edit'     => '\Project\Controllers\Admin\Submissions\Categories@edit',
    $ap . '/submissions/categories/delete'   => '\Project\Controllers\Admin\Submissions\Categories@delete',
    $ap . '/submissions/categories'          => '\Project\Controllers\Admin\Submissions\Categories',
    // submissions
    $ap . '/submissions/create'              => '\Project\Controllers\Admin\Submissions\Create',
    $ap . '/submissions/edit'                => '\Project\Controllers\Admin\Submissions\Edit',
    $ap . '/submissions/like'                => '\Project\Controllers\Admin\Submissions\Like',
    $ap . '/submissions/activity'            => '\Project\Controllers\Admin\Submissions\Activity',
    $ap . '/submissions/comment'             => '\Project\Controllers\Admin\Submissions\Comment',
    $ap . '/submissions/accept'              => '\Project\Controllers\Admin\Submissions\Accept',
    $ap . '/submissions/email'               => '\Project\Controllers\Admin\Submissions\Email',
    $ap . '/submissions/show'                => '\Project\Controllers\Admin\Submissions\Show',
    $ap . '/submissions/assets'              => '\Project\Controllers\Admin\Submissions\Assets',
    $ap . '/submissions/download'            => '\Project\Controllers\Admin\Submissions\Download',
    $ap . '/submissions/template'            => '\Project\Controllers\Admin\Submissions\Template',
    $ap . '/submissions/export'              => '\Project\Controllers\Admin\Submissions\Export',

    $ap . '/submissions'                     => '\Project\Controllers\Admin\Submissions\Index',
    // subscriptions categories
    $ap . '/subscriptions/categories/create' => '\Project\Controllers\Admin\Subscriptions\Categories@create',
    $ap . '/subscriptions/categories/delete' => '\Project\Controllers\Admin\Subscriptions\Categories@delete',
    $ap . '/subscriptions/categories/edit'   => '\Project\Controllers\Admin\Subscriptions\Categories@edit',
    $ap . '/subscriptions/categories'        => '\Project\Controllers\Admin\Subscriptions\Categories',
    // subscriptions
    $ap . '/subscriptions/create'            => '\Project\Controllers\Admin\Subscriptions\Create',
    $ap . '/subscriptions/delete'            => '\Project\Controllers\Admin\Subscriptions\Delete',
    $ap . '/subscriptions/edit'              => '\Project\Controllers\Admin\Subscriptions\Edit',
    $ap . '/subscriptions/export'            => '\Project\Controllers\Admin\Subscriptions\Export',
    $ap . '/subscriptions/show'              => '\Project\Controllers\Admin\Subscriptions\Show',
    $ap . '/subscriptions'                   => '\Project\Controllers\Admin\Subscriptions\Index',
    // orders
//    $ap . '/orders/create'                   => '\Project\Controllers\Admin\Orders\Create',
//    $ap . '/orders/delete'                   => '\Project\Controllers\Admin\Orders\Delete',
    $ap . '/orders/edit'                     => '\Project\Controllers\Admin\Orders\Edit',
    $ap . '/orders/export'                     => '\Project\Controllers\Admin\Orders\Export',
    $ap . '/orders'                          => '\Project\Controllers\Admin\Orders\Index',
    // issues
    $ap . '/issues/create'                   => '\Project\Controllers\Admin\Issues\Create',
	$ap . '/issues/createv1'                   => '\Project\Controllers\Admin\Issues\CreateV1',
    $ap . '/issues/show'                     => '\Project\Controllers\Admin\Issues\Show',
    $ap . '/issues/edit'                     => '\Project\Controllers\Admin\Issues\Edit',
    $ap . '/issues/toc'                      => '\Project\Controllers\Admin\Issues\Toc',
    $ap . '/issues/content/edit'             => '\Project\Controllers\Admin\Issues\ContentEdit',
    $ap . '/issues/content/create'           => '\Project\Controllers\Admin\Issues\ContentCreate',
    $ap . '/issues/content'                  => '\Project\Controllers\Admin\Issues\Content',
    $ap . '/issues/delete'                   => '\Project\Controllers\Admin\Issues\Delete',
    $ap . '/issues'                          => '\Project\Controllers\Admin\Issues\Index',

 

    // chapbooks
    $ap . '/chapbooks/create'                => '\Project\Controllers\Admin\Chapbooks\Create',
    $ap . '/chapbooks/createv1'                => '\Project\Controllers\Admin\Chapbooks\CreateV1',
    $ap . '/chapbooks/show'                  => '\Project\Controllers\Admin\Chapbooks\Show',
    $ap . '/chapbooks/edit'                  => '\Project\Controllers\Admin\Chapbooks\Edit',
    $ap . '/chapbooks/toc'                   => '\Project\Controllers\Admin\Chapbooks\Toc',
    $ap . '/chapbooks/content/edit'          => '\Project\Controllers\Admin\Chapbooks\ContentEdit',
    $ap . '/chapbooks/content/create'        => '\Project\Controllers\Admin\Chapbooks\ContentCreate',
    $ap . '/chapbooks/content'               => '\Project\Controllers\Admin\Chapbooks\Content',
    $ap . '/chapbooks/delete'                => '\Project\Controllers\Admin\Chapbooks\Delete',
    $ap . '/chapbooks'                       => '\Project\Controllers\Admin\Chapbooks\Index',
    // pages
    $ap . '/pages/create'                    => '\Project\Controllers\Admin\Pages\Create',
    $ap . '/pages/edit/title'                => '\Project\Controllers\Admin\Pages\Edit@title',
    $ap . '/pages/edit/slug'                 => '\Project\Controllers\Admin\Pages\Edit@slug',
    $ap . '/pages/edit'                      => '\Project\Controllers\Admin\Pages\Edit',
    $ap . '/pages/delete'                    => '\Project\Controllers\Admin\Pages\Delete',
    $ap . '/editorpages'                     => '\Project\Controllers\Admin\Pages\EditorIndex',
    $ap . '/pages'                           => '\Project\Controllers\Admin\Pages\Index',
    // snippets
    $ap . '/snippets/create'                 => '\Project\Controllers\Admin\Pages\Snippets@create',
    $ap . '/snippets/edit'                   => '\Project\Controllers\Admin\Pages\Snippets@edit',
    $ap . '/snippets/delete'                 => '\Project\Controllers\Admin\Pages\Snippets@delete',
    $ap . '/editorsnippets'                  => '\Project\Controllers\Admin\Pages\EditorSnippetsIndex',
    $ap . '/snippets'                        => '\Project\Controllers\Admin\Pages\Snippets',

	$ap . '/podcast/create'                 => '\Project\Controllers\Admin\Pages\Podcast@create',
	$ap . '/podcast/edit'                   => '\Project\Controllers\Admin\Pages\Podcast@edit',
	$ap . '/podcast/delete'                 => '\Project\Controllers\Admin\Pages\Podcast@delete',
	$ap . '/editorpodcast'                  => '\Project\Controllers\Admin\Pages\EditorPodcastIndex',
	$ap . '/podcast'                        => '\Project\Controllers\Admin\Pages\Podcast',

	$ap . '/aboutus/create'                 => '\Project\Controllers\Admin\Pages\Aboutus@create',
	$ap . '/aboutus/edit'                   => '\Project\Controllers\Admin\Pages\Aboutus@edit',
	$ap . '/aboutus/delete'                 => '\Project\Controllers\Admin\Pages\Aboutus@delete',
	$ap . '/editoraboutus'                  => '\Project\Controllers\Admin\Pages\EditorAboutusIndex',
	$ap . '/aboutus'                        => '\Project\Controllers\Admin\Pages\Aboutus',



    // files
    $ap . '/files/create'                    => '\Project\Controllers\Admin\Files\Create',
    $ap . '/files/editorcreate'              => '\Project\Controllers\Admin\Files\EditorCreate',
    $ap . '/files/edit'                      => '\Project\Controllers\Admin\Files\Edit',
    $ap . '/files/delete'                    => '\Project\Controllers\Admin\Files\Delete',
    $ap . '/editorfiles'                     => '\Project\Controllers\Admin\Files\EditorIndex',
    $ap . '/files'                           => '\Project\Controllers\Admin\Files\Index',
    // menus
    $ap . '/menus/create'                    => '\Project\Controllers\Admin\Menus\Create',
    $ap . '/menus/edit'                      => '\Project\Controllers\Admin\Menus\Edit',
    $ap . '/menus/delete'                    => '\Project\Controllers\Admin\Menus\Delete',
    $ap . '/menus'                           => '\Project\Controllers\Admin\Menus\Index',
    // newsletter subscribers
    $ap . '/newsletter/subscribers/create'   => '\Project\Controllers\Admin\News\Subscribers@create',
    $ap . '/newsletter/subscribers/edit'     => '\Project\Controllers\Admin\News\Subscribers@edit',
    $ap . '/newsletter/subscribers/delete'   => '\Project\Controllers\Admin\News\Subscribers@delete',
    $ap . '/newsletter/subscribers'          => '\Project\Controllers\Admin\News\Subscribers',
    // newsletter
    $ap . '/newsletter/create'               => '\Project\Controllers\Admin\News\Newsletter@create',
    $ap . '/newsletter/edit'                 => '\Project\Controllers\Admin\News\Newsletter@edit',
    $ap . '/newsletter/delete'               => '\Project\Controllers\Admin\News\Newsletter@delete',
    $ap . '/newsletter'                      => '\Project\Controllers\Admin\News\Newsletter',
    // news
    $ap . '/news/create'                     => '\Project\Controllers\Admin\News\Index@create',
    $ap . '/news/edit'                       => '\Project\Controllers\Admin\News\Index@edit',
    $ap . '/news/delete'                     => '\Project\Controllers\Admin\News\Index@delete',
    $ap . '/news'                            => '\Project\Controllers\Admin\News\Index',
    // preferences
    $ap . '/preferences'                     => '\Project\Controllers\Admin\Preferences\Index',
    // templates
    $ap . '/templates/create'                => '\Project\Controllers\Admin\Templates\Create',
    $ap . '/templates/edit'                  => '\Project\Controllers\Admin\Templates\Edit',
    $ap . '/templates/delete'                => '\Project\Controllers\Admin\Templates\Delete',
    $ap . '/templates/item'                  => '\Project\Controllers\Admin\Templates\Item',
    $ap . '/templates'                       => '\Project\Controllers\Admin\Templates\Index',
    // users
    $ap . '/users/create'                    => '\Project\Controllers\Admin\Users\Create',
    $ap . '/users/biography'                 => '\Project\Controllers\Admin\Users\Biography',
    $ap . '/users/contact'                   => '\Project\Controllers\Admin\Users\Contact',
    $ap . '/users/delete'                    => '\Project\Controllers\Admin\Users\Delete',
    $ap . '/users/email'                     => '\Project\Controllers\Admin\Users\Email',
    $ap . '/users/password'                  => '\Project\Controllers\Admin\Users\Password',
    $ap . '/users/roles'                     => '\Project\Controllers\Admin\Users\Roles',
    $ap . '/users/edit'                      => '\Project\Controllers\Admin\Users\Edit',
    $ap . '/users'                           => '\Project\Controllers\Admin\Users\Index',
    // roles
    $ap . '/roles/create'                    => '\Project\Controllers\Admin\Roles\Create',
    $ap . '/roles/edit'                      => '\Project\Controllers\Admin\Roles\Edit',
    $ap . '/roles'                           => '\Project\Controllers\Admin\Roles\Index',
    // exporters
    $ap . '/exporters/create'                => '\Project\Controllers\Admin\Exporters\Create',
    $ap . '/exporters/edit'                  => '\Project\Controllers\Admin\Exporters\Edit',
    $ap . '/exporters/delete'                => '\Project\Controllers\Admin\Exporters\Delete',
    $ap . '/exporters'                       => '\Project\Controllers\Admin\Exporters\Index',
    // backup/restore
    $ap . '/backup/download'                 => '\Project\Controllers\Admin\Backup\Download',
    $ap . '/backup/restore'                  => '\Project\Controllers\Admin\Backup\Restore',
    $ap . '/backup/delete'                   => '\Project\Controllers\Admin\Backup\Delete',
    $ap . '/backup'                          => '\Project\Controllers\Admin\Backup\Index',
    // about
    $ap . '/logs'                            => '\Project\Controllers\Admin\Logs',
    $ap . '/about'                           => '\Project\Controllers\Admin\About',
    // dashboard
    $ap                                      => '\Project\Controllers\Admin\Dashboard',
    // --------------------------------------------------------------
    // Page routes (catch all)
    // --------------------------------------------------------------
    'contact'                                => '\Project\Controllers\ContactController',
    '/^([a-zA-Z0-9_-]+)/'                    => '\Project\Controllers\PageController',
    // Example paths
    //'example/path'		=> '\Controller\Example\Hander',
    //'example/([^/]+)'	=> '\Controller\Example\Param',
);
