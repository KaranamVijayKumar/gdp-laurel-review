<?php
/**
 * File: role_categories.php
 * Created: 06-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

$repo = new \Project\Support\PermissionRepository(__DIR__ . DIRECTORY_SEPARATOR . 'permissions.json');

/*
|--------------------------------------------------------------------------
| Role permission translations
|--------------------------------------------------------------------------
|
| Add more role permission names group by category name.
|
*/

return array(
    // --------------------------------------------------------------
    // Visitor
    // --------------------------------------------------------------
    _('Pages')            => array(
        $repo->toString('pages.index') => _('Welcome Page'),
        $repo->toString('pages.pages') => _('Pages'),
    ),
    // --------------------------------------------------------------
    // Issues
    // --------------------------------------------------------------
    _('Issues')           => array(
        $repo->toString('issues.pages') => 'Issues page',
        $repo->toString('issues.order') => 'Order issue',
    ),

    // --------------------------------------------------------------
    // Issues
    // --------------------------------------------------------------
    _('Chapbooks')           => array(
        $repo->toString('chapbooks.pages') => 'Chapbooks page',
        $repo->toString('chapbooks.order') => 'Chapbooks issue',
    ),

    // --------------------------------------------------------------
    // News
    // --------------------------------------------------------------
    _('News')             => array(
        $repo->toString('news.pages') => 'News pages',
    ),

    // --------------------------------------------------------------
    // Newsletter
    // --------------------------------------------------------------
    _('Newsletter')             => array(
        $repo->toString('newsletter.pages') => 'Newsletter subscribe/unsubscribe',
    ),
    // --------------------------------------------------------------
    // Cart
    // --------------------------------------------------------------
    _('Shopping Cart')             => array(
        $repo->toString('cart') => 'Shopping Cart',
    ),
    // --------------------------------------------------------------
    // Contact
    // --------------------------------------------------------------
    _('Contact')          => array(
        $repo->toString('contact.pages') => 'Contact page',
    ),
    _('Account creation') => array(
        $repo->toString('account.create') => _('Enabled')
    ),
    // --------------------------------------------------------------
    // Account
    // --------------------------------------------------------------
    _('Account')          => array(
        $repo->toString('account.dashboard') => _('Account Dashboard'),
        $repo->toString('account.biography') => _('Biography'),
        $repo->toString('account.email')     => _('Name and Email address'),
        $repo->toString('account.password')  => _('Password'),
        $repo->toString('account.contact')   => _('Contact Information'),
        $repo->toString('account.delete')    => _('Delete Account'),
    ),
    // --------------------------------------------------------------
    // Submissions
    // --------------------------------------------------------------
    _('Submissions')      => array(
        $repo->toString('submissions.view')     => _('View'),
        $repo->toString('submissions.create')   => _('Create'),
        $repo->toString('submissions.sign')     => _('Sign submission'),
        $repo->toString('submissions.withdraw') => _('Withdraw submission'),
    ),
    // --------------------------------------------------------------
    // Subscriptions
    // --------------------------------------------------------------

    _('Subscriptions')    => array(
        $repo->toString('subscriptions.view')   => _('View'),
        $repo->toString('subscriptions.create') => _('Create'),
        $repo->toString('subscriptions.cancel') => _('Cancel Subscription'),
    ),
    // --------------------------------------------------------------
    // Admin
    // --------------------------------------------------------------
    _('Administration')   => array(

        // dashboard
        $repo->toString('admin.dashboard') => _('Dashboard'),
        // account
        _('Account')                       => array(
            $repo->toString('admin.account.dashboard') => _('Account Dashboard'),
            $repo->toString('admin.account.biography') => _('Biography'),
            $repo->toString('admin.account.email')     => _('Name and Email address'),
            $repo->toString('admin.account.password')  => _('Password'),
            $repo->toString('admin.account.contact')   => _('Contact Information'),
            $repo->toString('admin.account.delete')    => _('Delete Account'),
        ),
        // submissions
        _('Submissions') => array(
            $repo->toString('admin.submissions.view')        => _('View'),
            $repo->toString('admin.submissions.download')    => _('Download file'),
            $repo->toString('admin.submissions.create')      => _('Create'),
            $repo->toString('admin.submissions.edit')        => _('Edit properties'),
            $repo->toString('admin.submissions.like')        => _('Like'),
            $repo->toString('admin.submissions.accept')      => _('Accept / Decline'),
            $repo->toString('admin.submissions.comment')     => _('Add comments'),
            $repo->toString('admin.submissions.view_author') => _('View Author and Coverletter'),
            $repo->toString('admin.submissions.send_email')  => _('Send emails to submitter'),
            $repo->toString('admin.submissions.export')      => _('Export'),

            // categories
            $repo->toString('admin.submissions.manage_categories', 'admin.editor_assets') => _('Manage Categories'),
        ),
        // subscriptions
        _('Subscriptions')                 => array(
            $repo->toString('admin.subscriptions.view')              => _('View'),
            $repo->toString('admin.subscriptions.create')            => _('Create'),
            $repo->toString('admin.subscriptions.edit')              => _('Edit'),
            $repo->toString('admin.subscriptions.delete')            => _('Delete'),
            $repo->toString('admin.subscriptions.export')            => _('Export'),
            // categories
            $repo->toString('admin.subscriptions.manage_categories') => _('Manage Categories'),
        ),
        // Orders
        _('Orders')                        => array(
            $repo->toString('admin.orders.view')                          => _('View'),
            $repo->toString('admin.orders.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.orders.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.orders.delete')                        => _('Delete'),
            $repo->toString('admin.orders.export')            => _('Export'),
        ),
        // Issues
        _('Issues')                        => array(
            $repo->toString('admin.issues.view')                          => _('View'),
            $repo->toString('admin.issues.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.issues.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.issues.delete')                        => _('Delete'),
        ),

        _('Chapbooks')                        => array(
            $repo->toString('admin.chapbooks.view')                          => _('View'),
            $repo->toString('admin.chapbooks.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.chapbooks.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.chapbooks.delete')                        => _('Delete'),
        ),
        // Pages
        _('Pages')                         => array(
            $repo->toString('admin.pages.view')                          => _('View'),
            $repo->toString('admin.pages.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.pages.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.pages.delete')                        => _('Delete'),

        ),
        // Snippets
        _('Snippets')                      => array(
            $repo->toString('admin.snippets.view')                          => _('View'),
            $repo->toString('admin.snippets.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.snippets.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.snippets.delete')                        => _('Delete'),
        ),
        // Files
        _('Files')                         => array(
            $repo->toString('admin.files.view')   => _('View'),
            $repo->toString('admin.files.upload') => _('Upload'),
            $repo->toString('admin.files.edit')   => _('Edit'),
            $repo->toString('admin.files.delete') => _('Delete'),
        ),
        // News
        _('News')                          => array(
            $repo->toString('admin.news.view')                          => _('View'),
            $repo->toString('admin.news.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.news.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.news.delete')                        => _('Delete'),
        ),
        // Newsletter
        _('Newsletters')                   => array(
            $repo->toString('admin.newsletter.view')                          => _('View'),
            $repo->toString('admin.newsletter.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.newsletter.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.newsletter.delete')                        => _('Delete'),
        ),
        // Newsletter subscribers
        _('Newsletters subscribers')       => array(
            $repo->toString('admin.newsletter.subscribers.view')   => _('View'),
            $repo->toString('admin.newsletter.subscribers.create') => _('Create'),
            $repo->toString('admin.newsletter.subscribers.edit')   => _('Edit'),
            $repo->toString('admin.newsletter.subscribers.delete') => _('Delete'),
        ),
        // preferences
        _('Preferences')                   => array(
            $repo->toString('admin.preferences.edit') => _('Manage')
        ),
        // templates
        _('Templates')                     => array(
            $repo->toString('admin.templates.view')                          => _('View'),
            $repo->toString('admin.templates.create', 'admin.editor_assets') => _('Create'),
            $repo->toString('admin.templates.edit', 'admin.editor_assets')   => _('Edit'),
            $repo->toString('admin.templates.delete')                        => _('Delete'),
        ),
        // users
        _('Users')                         => array(
            $repo->toString('admin.users.view')      => _('View'),
            $repo->toString('admin.users.create')    => _('Create'),
            $repo->toString('admin.users.edit')      => _('User Dashboard'),
            $repo->toString('admin.users.biography') => _('Biography'),
            $repo->toString('admin.users.email')     => _('Name and Email address'),
            $repo->toString('admin.users.password')  => _('Password'),
            $repo->toString('admin.users.contact')   => _('Contact Information'),
            $repo->toString('admin.users.roles')     => _('Roles'),
            $repo->toString('admin.users.delete')    => _('Delete Account'),
        ),
        // roles
        _('Roles')                         => array(
            $repo->toString('admin.roles.view')   => _('View'),
            $repo->toString('admin.roles.create') => _('Create'),
            $repo->toString('admin.roles.edit')   => _('Edit'),
            $repo->toString('admin.roles.delete') => _('Delete'),
        ),
        // exporters
        _('Exports')                         => array(
            $repo->toString('admin.exporters.view')   => _('View'),
            $repo->toString('admin.exporters.create') => _('Create'),
            $repo->toString('admin.exporters.edit')   => _('Edit'),
            $repo->toString('admin.exporters.delete') => _('Delete'),
        ),
        // backup/restore
        _('Backup/Restore')                => array(
            $repo->toString('admin.backup.create')   => _('Perform full backup'),
            $repo->toString('admin.backup.download') => _('Download backup files'),
            $repo->toString('admin.backup.restore')  => _('Restore from backup files'),
            $repo->toString('admin.backup.delete')   => _('Delete backup files'),

        ),
        // about
        _('About')                         => array(
            $repo->toString('admin.about') => _('View'),
            $repo->toString('admin.log')   => _('Log files'),
        ),
    ),
);
