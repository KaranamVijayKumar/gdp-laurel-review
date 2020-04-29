<?php
/**
 * File: migrations.php
 * Created: 27-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

use Project\Models\Chapbook;
use Project\Models\ChapbookContent;
use Project\Models\ChapbookFile;
use Project\Models\ChapbookToc;
use Project\Models\ChapbookTocContent;
use Project\Models\ChapbookTocTitle;
use Project\Models\Export;
use Project\Models\Issue;
use Project\Models\IssueContent;
use Project\Models\IssueFile;
use Project\Models\IssueToc;
use Project\Models\IssueTocContent;
use Project\Models\IssueTocTitle;
use Project\Models\Log;
use Project\Models\Menu;
use Project\Models\News;
use Project\Models\NewsContent;
use Project\Models\Newsletter;
use Project\Models\NewsletterContent;
use Project\Models\NewsletterSubscriber;
use Project\Models\NewsletterSubscriberPivot;
use Project\Models\Order;
use Project\Models\OrderItem;
use Project\Models\OrderUser;
use Project\Models\Page;
use Project\Models\PageContent;
use Project\Models\PageData;
use Project\Models\PageMeta;
use Project\Models\PasswordReminder;
use Project\Models\Payment;
use Project\Models\PrivateAsset;
use Project\Models\Profile;
use Project\Models\PublicAsset;
use Project\Models\Role;
use Project\Models\Snippet;
use Project\Models\Submission;
use Project\Models\SubmissionCategory;
use Project\Models\SubmissionComment;
use Project\Models\SubmissionCoverletter;
use Project\Models\SubmissionEmail;
use Project\Models\SubmissionFile;
use Project\Models\SubmissionLike;
use Project\Models\SubmissionPartial;
use Project\Models\SubmissionStatus;
use Project\Models\Subscription;
use Project\Models\SubscriptionCategory;
use Project\Models\Template;
use Project\Models\User;
use Project\Models\UserBiography;
use Project\Models\UserData;
use Project\Models\UserRole;
use StoryCart\CartItemRepository;
use StoryCart\CartRepository;

return array(

    // --------------------------------------------------------------
    // System tables
    // --------------------------------------------------------------
    /**
     * Config table
     */
    'config'                              => array(
        'id'    => array('type' => 'primary', 'unsigned' => true),
        'name'  => array('type' => 'string', 'length' => 100, 'unique' => true),
        'value' => array('type' => 'string'),
        'type'  => array('type' => 'string', 'length' => 20),
    ),
    /**
     * Sessions table
     */
    'sessions'                            => array(
        '__collate'     => 'utf8_bin',
        'id'            => array('type' => 'string', 'unique' => true, 'length' => 255),
        'payload'       => array('type' => 'string', 'length' => 16777215),
        'last_activity' => array('type' => 'integer', 'unsigned' => true, 'index' => true),
    ),
    /**
     * Email templates
     */
    Template::getTable()                  => array(
        'id'          => array('type' => 'primary', 'unsigned' => true),
        'locked'      => array('type' => 'integer', 'unsigned' => true, 'length' => 1),
        'type'        => array('type' => 'string', 'length' => 45, 'index' => true),
        'name'        => array('type' => 'string', 'length' => 255, 'index' => true),
        'subject'     => array('type' => 'string', 'length' => 255),
        'message'     => array('type' => 'string', 'length' => 65535),
        'variables'   => array('type' => 'string', 'length' => 65535),
        'description' => array('type' => 'string', 'length' => 65535),
        'created'     => array('type' => 'integer', 'unsigned' => true),
        'modified'    => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Logs
     */
    Log::getTable()                       => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'loggable_id'   => array('type' => 'integer', 'index' => true, 'unsigned' => true),
        'loggable_type' => array('type' => 'string', 'length' => 255, 'index' => true),
        'message'       => array('type' => 'string', 'length' => 255),
        'payload'       => array('type' => 'string', 'length' => 16777215),
        'created'       => array('type' => 'integer', 'unsigned' => true),
        'modified'      => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Spam protector tables
    // --------------------------------------------------------------

    'sp_email_lists'                      => array(
        'address' => array('type' => 'string', 'length' => 255, 'index' => true),
    ),
    'sp_ip_lists'                         => array(
        'address' => array('type' => 'string', 'length' => 255, 'index' => true),
    ),
    'sp_fields'                           => array(
        'id'       => array('type' => 'string', 'length' => 255, 'index' => true),
        'data'     => array('type' => 'string', 'length' => 65535),
        'created'  => array('type' => 'integer', 'unsigned' => true),
        'modified' => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // User related tables
    // --------------------------------------------------------------
    /**
     * password reminders table
     */
    PasswordReminder::getTable()          => array(
        '__collate' => 'utf8_bin',
        'email'     => array('type' => 'string', 'index' => true, 'length' => 255),
        'token'     => array('type' => 'string', 'index' => true, 'length' => 255),
        'created'   => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * users table
     */
    User::getTable()                      => array(
        'id'               => array('type' => 'primary', 'unsigned' => true),
        'email'            => array('type' => 'string', 'unique' => true, 'length' => 255),
        'password'         => array('type' => 'string', 'length' => 65535),
        'change_password'  => array('type' => 'boolean', 'index' => true),
        'update_profile'   => array('type' => 'boolean', 'index' => true),
        'active'           => array('type' => 'boolean', 'index' => true),
        'activation_token' => array('type' => 'string', 'index' => true, 'length' => 255),
        'remember_token'   => array('type' => 'string', 'length' => 65535),
        'last_login'       => array('type' => 'integer', 'unsigned' => true),
        'created'          => array('type' => 'integer', 'unsigned' => true),
        'modified'         => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * user profile table
     */
    Profile::getTable()                   => array(
        'id'      => array('type' => 'primary', 'unsigned' => true),
        'user_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'value'   => array('type' => 'string', 'length' => 255),
    ),
    /**
     * User data
     */
    UserData::getTable()                  => array(
        'id'      => array('type' => 'primary', 'unsigned' => true),
        'user_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'value'   => array('type' => 'string', 'length' => 65535),
    ),
    /**
     * User data
     */
    UserBiography::getTable()             => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'user_id'      => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'content'      => array('type' => 'string', 'length' => 16777215),
        'content_text' => array('type' => 'string', 'length' => 16777215),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Role tables
    // --------------------------------------------------------------
    /**
     * Roles table
     */
    Role::getTable()                      => array(
        'id'          => array('type' => 'primary', 'unsigned' => true),
        'name'        => array('type' => 'string', 'unique' => true, 'length' => 255),
        'default'     => array('type' => 'boolean', 'index' => true),
        'locked'      => array('type' => 'boolean'),
        'order'       => array('type' => 'integer', 'default' => 100, 'index' => true), // highest order number + 1
        'permissions' => array('type' => 'string', 'length' => 65535),
    ),
    /**
     * User roles
     */
    UserRole::getTable()                  => array(
        'id'      => array('type' => 'primary', 'unsigned' => true),
        'user_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'role_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Role::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
    ),
    // --------------------------------------------------------------
    // Submission tables
    // --------------------------------------------------------------
    /**
     * Submission categories
     */
    SubmissionCategory::getTable()        => array(
        'id'              => array('type' => 'primary', 'unsigned' => true),
        'name'            => array('type' => 'string', 'length' => 255, 'index' => true),
        'slug'            => array('type' => 'string', 'length' => 255, 'unique' => true),
        'guidelines'      => array('type' => 'string', 'length' => 65535),
        'guidelines_text' => array('type' => 'string', 'length' => 65535),
        'size_limit'      => array('type' => 'string', 'length' => 255),
        'amount'          => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'status'          => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'order'           => array('type' => 'integer', 'index' => true),
        'created'         => array('type' => 'integer', 'unsigned' => true),
        'modified'        => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission status
     */
    SubmissionStatus::getTable()          => array(
        'id'       => array('type' => 'primary', 'unsigned' => true),
        'name'     => array('type' => 'string', 'length' => 255, 'unique' => true),
        'slug'     => array('type' => 'string', 'length' => 255, 'unique' => true),
        'order'    => array('type' => 'integer', 'index' => true),
        'created'  => array('type' => 'integer', 'unsigned' => true),
        'modified' => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submissions
     */
    Submission::getTable()                => array(
        'id'                     => array('type' => 'primary', 'unsigned' => true),
        'user_id'                => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'submission_category_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => SubmissionCategory::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'submission_status_id'   => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => SubmissionStatus::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'name'                   => array('type' => 'string', 'length' => 255, 'index' => true),
        'created'                => array('type' => 'integer', 'unsigned' => true),
        'modified'               => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission files
     */
    SubmissionFile::getTable()            => array(
        '__collate'     => 'utf8_bin',
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'submission_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Submission::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'          => array('type' => 'string', 'length' => 255, 'index' => true),
        'storage_name'  => array('type' => 'string', 'length' => 255),
        'access_key'    => array('type' => 'string', 'length' => 100, 'index' => true),
        'preview_key'   => array('type' => 'string', 'length' => 255, 'index' => true),
        'mime'          => array('type' => 'string', 'length' => 255),
        'meta'          => array('type' => 'string', 'length' => 65535),
        'status'        => array('type' => 'boolean', 'index' => true),
        'created'       => array('type' => 'integer', 'unsigned' => true),
        'modified'      => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission likes
     */
    SubmissionLike::getTable()            => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'submission_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Submission::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'user_id'       => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'value'         => array('type' => 'boolean'),
        'created'       => array('type' => 'integer', 'unsigned' => true),
        'modified'      => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission emails
     */
    SubmissionEmail::getTable()           => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'submission_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Submission::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'user_id'       => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'message'       => array('type' => 'string', 'length' => 65535),
        'subject'       => array('type' => 'string', 'length' => 255),
        'created'       => array('type' => 'integer', 'unsigned' => true),
        'modified'      => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission partials
     */
    SubmissionPartial::getTable()         => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'submission_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Submission::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'content'       => array('type' => 'string', 'length' => 65535),
        'created'       => array('type' => 'integer', 'unsigned' => true),
        'modified'      => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission comments
     */
    SubmissionComment::getTable()         => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'submission_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Submission::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'user_id'       => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'message'       => array('type' => 'string', 'length' => 65535),
        'created'       => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Submission coverletters
     */
    SubmissionCoverletter::getTable()     => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'submission_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Submission::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'user_id'       => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'content'       => array('type' => 'string', 'length' => 65535),
        'created'       => array('type' => 'integer', 'unsigned' => true),
        'modified'      => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Menu tables
    // --------------------------------------------------------------
    /**
     * Menus table
     */
    Menu::getTable()                      => array(
        'id'              => array('type' => 'primary', 'unsigned' => true),
        'parent_id'       => array('type' => 'integer', 'unsigned' => true),
        'menu_name'       => array('type' => 'string', 'length' => 255, 'index' => true),
        'item_id'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'type'            => array('type' => 'string', 'length' => 30, 'index' => true),
        'access'          => array('type' => 'string', 'length' => 30, 'index' => true),
        'text'            => array('type' => 'string', 'length' => 65535),
        'url'             => array('type' => 'string', 'length' => 255, 'index' => true),
        'url_params'      => array('type' => 'string', 'length' => 65535),
        'html_attributes' => array('type' => 'string', 'length' => 65535),
        'status'          => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'order'           => array('type' => 'integer', 'unsigned' => true, 'index' => true),
        'created'         => array('type' => 'integer', 'unsigned' => true),
        'modified'        => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Snippets table
    // --------------------------------------------------------------
    Snippet::getTable()                   => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'slug'         => array('type' => 'string', 'unique' => true, 'length' => 255),
        'description'  => array('type' => 'string', 'length' => 255),
        'content'      => array('type' => 'string', 'length' => 16777215),
        'content_text' => array('type' => 'string', 'length' => 16777215),
        'status'       => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Page tables
    // --------------------------------------------------------------
    /**
     * Pages table
     */
    Page::getTable()                      => array(
        'id'       => array('type' => 'primary', 'unsigned' => true),
        'slug'     => array('type' => 'string', 'unique' => true, 'length' => 255),
        'view'     => array('type' => 'string', 'length' => 255, 'default' => 'page'),
        'status'   => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'locked'   => array('type' => 'boolean', 'default' => '0', 'index' => true),
        'created'  => array('type' => 'integer', 'unsigned' => true),
        'modified' => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Page content table
     */
    PageContent::getTable()               => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'page_id'      => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Page::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'         => array('type' => 'string', 'length' => 255, 'index' => true, 'default' => 'content'),
        'locale'       => array('type' => 'string', 'length' => 45, 'index' => true),
        'title'        => array('type' => 'string', 'length' => 255),
        'content'      => array('type' => 'string', 'length' => 16777215),
        'content_text' => array('type' => 'string', 'length' => 16777215),
    ),
    /**
     * Page meta
     */
    PageMeta::getTable()                  => array(
        'id'      => array('type' => 'primary', 'unsigned' => true),
        'page_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Page::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'value'   => array('type' => 'string', 'length' => 255),
    ),
    /**
     * Page data
     */
    PageData::getTable()                  => array(
        'id'      => array('type' => 'primary', 'unsigned' => true),
        'page_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Page::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'value'   => array('type' => 'string', 'length' => 65535),
    ),
    // --------------------------------------------------------------
    // Newsletter tables
    // --------------------------------------------------------------

    /**
     * Newsletters
     */
    Newsletter::getTable()                => array(
        'id'          => array('type' => 'primary', 'unsigned' => true),
        'template_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Template::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'status'      => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'sent'        => array('type' => 'integer', 'unsigned' => true),
        'notes'       => array('type' => 'string', 'length' => 255),
        'created'     => array('type' => 'integer', 'unsigned' => true),
        'modified'    => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Newsletter content
     */
    NewsletterContent::getTable()         => array(
        'id'            => array('type' => 'primary', 'unsigned' => true),
        'newsletter_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Newsletter::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'subject'       => array('type' => 'string', 'length' => 255),
        'content'       => array('type' => 'string', 'length' => 16777215),
        'content_text'  => array('type' => 'string', 'length' => 16777215),
    ),
    /**
     * Newsletter subscribers
     */
    NewsletterSubscriber::getTable()      => array(
        'id'       => array('type' => 'primary', 'unsigned' => true),
        'email'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'created'  => array('type' => 'integer', 'unsigned' => true),
        'modified' => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Newsletter subscribers pivot
     */
    NewsletterSubscriberPivot::getTable() => array(
        'id'                       => array('type' => 'primary', 'unsigned' => true),
        'newsletter_id'            => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Newsletter::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'newsletter_subscriber_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'default'  => null,
            'foreign'  => array(
                'table'   => NewsletterSubscriber::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
    ),
    // --------------------------------------------------------------
    // News tables
    // --------------------------------------------------------------
    /**
     * News table
     */
    News::getTable()                      => array(
        'id'       => array('type' => 'primary', 'unsigned' => true),
        'slug'     => array('type' => 'string', 'unique' => true, 'length' => 255),
        'status'   => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'created'  => array('type' => 'integer', 'unsigned' => true),
        'modified' => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Newsletter content
     */
    NewsContent::getTable()               => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'news_id'      => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => News::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'locale'       => array('type' => 'string', 'length' => 45, 'index' => true),
        'title'        => array('type' => 'string', 'length' => 255),
        'content'      => array('type' => 'string', 'length' => 16777215),
        'content_text' => array('type' => 'string', 'length' => 16777215),
    ),
    // --------------------------------------------------------------
    // Asset tables
    // --------------------------------------------------------------
    /**
     * Public assets
     */
    PublicAsset::getTable()               => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'name'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'storage_name' => array('type' => 'string', 'length' => 255),
        'mime'         => array('type' => 'string', 'length' => 255),
        'meta'         => array('type' => 'string', 'length' => 65535),
        'status'       => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Private assets
     */
    PrivateAsset::getTable()              => array(
        '__collate'    => 'utf8_bin',
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'name'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'storage_name' => array('type' => 'string', 'length' => 255),
        'access_key'   => array('type' => 'string', 'length' => 100, 'index' => true),
        'mime'         => array('type' => 'string', 'length' => 255),
        'meta'         => array('type' => 'string', 'length' => 65535),
        'status'       => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Payment tables
    // --------------------------------------------------------------
    /**
     * Payments
     */
    Payment::getTable()                   => array(
        '__collate'      => 'utf8_bin',
        'id'             => array('type' => 'primary', 'unsigned' => true),
        'payable_id'     => array('type' => 'integer', 'index' => true, 'unsigned' => true),
        'payable_type'   => array('type' => 'string', 'length' => 255, 'index' => true),
        'payment_status' => array('type' => 'string', 'length' => 255, 'index' => true),
        'payment_data'   => array('type' => 'string', 'length' => 65535),
        'transaction_id' => array('type' => 'string', 'length' => 255),
        'amount'         => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'notes'          => array('type' => 'string', 'length' => 65535),
        'created'        => array('type' => 'integer', 'unsigned' => true),
        'modified'       => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Issues tables
    // --------------------------------------------------------------
    /**
     * Issues
     */
    Issue::getTable()                     => array(
        'id'        => array('type' => 'primary', 'unsigned' => true),
        'slug'      => array('type' => 'string', 'length' => 255, 'index' => true),
        'title'     => array('type' => 'string', 'length' => 255, 'index' => true),
//        'amount'   => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'status'    => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'inventory' => array('type' => 'integer', 'unsigned' => true, 'index' => true),
        'created'   => array('type' => 'integer', 'unsigned' => true),
        'modified'  => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Issue files
     */
    IssueFile::getTable()                 => array(
        '__collate'      => 'utf8_bin',
        'id'             => array('type' => 'primary', 'unsigned' => true),
        'issueable_id'   => array('type' => 'integer', 'index' => true, 'unsigned' => true),
        'issueable_type' => array('type' => 'string', 'length' => 255, 'index' => true),
        'name'           => array('type' => 'string', 'length' => 255, 'index' => true),
        'storage_name'   => array('type' => 'string', 'length' => 255),
        'access_key'     => array('type' => 'string', 'length' => 100, 'index' => true),
        'preview_key'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'mime'           => array('type' => 'string', 'length' => 255),
        'meta'           => array('type' => 'string', 'length' => 65535),
        'status'         => array('type' => 'boolean', 'index' => true),
        'created'        => array('type' => 'integer', 'unsigned' => true),
        'modified'       => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Issue content
     */
    IssueContent::getTable()              => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'issue_id'     => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Issue::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'title'        => array('type' => 'string', 'length' => 255),
        'content'      => array('type' => 'string', 'length' => 16777215),
        'content_text' => array('type' => 'string', 'length' => 16777215),
    ),
    /**
     * Issue toc
     */
    IssueToc::getTable()                  => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'issue_id'     => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Issue::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'order'        => array('type' => 'integer', 'index' => true),
        'is_header'    => array('type' => 'boolean', 'default' => '0', 'index' => true),
        'content'      => array('type' => 'string', 'length' => 65535),
        'content_text' => array('type' => 'string', 'length' => 65535),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Issue toc titles
     */
    IssueTocTitle::getTable()             => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'issue_toc_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => IssueToc::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'order'        => array('type' => 'integer', 'index' => true),
        'content'      => array('type' => 'string', 'length' => 65535),
        'content_text' => array('type' => 'string', 'length' => 65535),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Issue toc content
     */
    IssueTocContent::getTable()           => array(
        'id'                 => array('type' => 'primary', 'unsigned' => true),
        'issue_id'           => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Issue::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'issue_toc_title_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => IssueTocTitle::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'slug'               => array('type' => 'string', 'length' => 255, 'index' => true),
        'status'             => array('type' => 'boolean', 'index' => true),
        'highlight'          => array('type' => 'boolean', 'index' => true),
        'content'            => array('type' => 'string', 'length' => 65535),
        'content_text'       => array('type' => 'string', 'length' => 65535),
        'created'            => array('type' => 'integer', 'unsigned' => true),
        'modified'           => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Chapbook tables
    // --------------------------------------------------------------
    /**
     * Chapbooks
     */
    Chapbook::getTable()                  => array(
        'id'        => array('type' => 'primary', 'unsigned' => true),
        'slug'      => array('type' => 'string', 'length' => 255, 'index' => true),
        'title'     => array('type' => 'string', 'length' => 255, 'index' => true),
        'status'    => array('type' => 'string', 'length' => 255, 'default' => '1', 'index' => true),
        'inventory' => array('type' => 'integer', 'unsigned' => true, 'index' => true),
        'created'   => array('type' => 'integer', 'unsigned' => true),
        'modified'  => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Chapbook files
     */
    ChapbookFile::getTable()              => array(
        '__collate'         => 'utf8_bin',
        'id'                => array('type' => 'primary', 'unsigned' => true),
        'chapbookable_id'   => array('type' => 'integer', 'index' => true, 'unsigned' => true),
        'chapbookable_type' => array('type' => 'string', 'length' => 255, 'index' => true),
        'name'              => array('type' => 'string', 'length' => 255, 'index' => true),
        'storage_name'      => array('type' => 'string', 'length' => 255),
        'access_key'        => array('type' => 'string', 'length' => 100, 'index' => true),
        'preview_key'       => array('type' => 'string', 'length' => 255, 'index' => true),
        'mime'              => array('type' => 'string', 'length' => 255),
        'meta'              => array('type' => 'string', 'length' => 65535),
        'status'            => array('type' => 'boolean', 'index' => true),
        'created'           => array('type' => 'integer', 'unsigned' => true),
        'modified'          => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Chapbook content
     */
    ChapbookContent::getTable()           => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'chapbook_id'  => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Chapbook::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'name'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'title'        => array('type' => 'string', 'length' => 255),
        'content'      => array('type' => 'string', 'length' => 16777215),
        'content_text' => array('type' => 'string', 'length' => 16777215),
    ),
    /**
     * Chapbook toc
     */
    ChapbookToc::getTable()               => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'chapbook_id'  => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Chapbook::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'order'        => array('type' => 'integer', 'index' => true),
        'is_header'    => array('type' => 'boolean', 'default' => '0', 'index' => true),
        'content'      => array('type' => 'string', 'length' => 65535),
        'content_text' => array('type' => 'string', 'length' => 65535),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Chapbook toc titles
     */
    ChapbookTocTitle::getTable()          => array(
        'id'              => array('type' => 'primary', 'unsigned' => true),
        'chapbook_toc_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => ChapbookToc::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'order'           => array('type' => 'integer', 'index' => true),
        'content'         => array('type' => 'string', 'length' => 65535),
        'content_text'    => array('type' => 'string', 'length' => 65535),
        'created'         => array('type' => 'integer', 'unsigned' => true),
        'modified'        => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Chapbook toc content
     */
    ChapbookTocContent::getTable()        => array(
        'id'                    => array('type' => 'primary', 'unsigned' => true),
        'chapbook_id'           => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Chapbook::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'chapbook_toc_title_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => ChapbookTocTitle::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'slug'                  => array('type' => 'string', 'length' => 255, 'index' => true),
        'status'                => array('type' => 'boolean', 'index' => true),
        'highlight'             => array('type' => 'boolean', 'index' => true),
        'content'               => array('type' => 'string', 'length' => 65535),
        'content_text'          => array('type' => 'string', 'length' => 65535),
        'created'               => array('type' => 'integer', 'unsigned' => true),
        'modified'              => array('type' => 'integer', 'unsigned' => true),
    ),
    // --------------------------------------------------------------
    // Cart
    // --------------------------------------------------------------
    CartRepository::getTable()            => array(
        'id'         => array('type' => 'primary', 'unsigned' => true),
        'session_id' => array('type' => 'string', 'index' => true, 'length' => 255),
        'user_id'    => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'default'  => null,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'created'    => array('type' => 'integer', 'unsigned' => true),
        'modified'   => array('type' => 'integer', 'unsigned' => true),
    ),
    CartItemRepository::getTable()        => array(
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'cart_id'      => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'default'  => null,
            'foreign'  => array(
                'table'   => CartRepository::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'type'         => array('type' => 'string', 'length' => 255, 'index' => true),
        'type_id'      => array('type' => 'string', 'length' => 255, 'index' => true),
        'type_payload' => array('type' => 'string', 'length' => 16777215),
        'type_assets'  => array('type' => 'string', 'length' => 16777215),
        'quantity'     => array('type' => 'integer', 'index' => true),
        'price'        => array('type' => 'decimal', 'precision' => 10, 'scale' => 2, 'index' => true),
        'tax'          => array('type' => 'decimal', 'precision' => 10, 'scale' => 2, 'index' => true),
        'currency'     => array('type' => 'string', 'length' => 255, 'index' => true),

    ),
    // --------------------------------------------------------------
    // Order
    // --------------------------------------------------------------
    /**
     * Order
     */
    Order::getTable()                     => array(
        '__collate'    => 'utf8_bin',
        'id'           => array('type' => 'primary', 'unsigned' => true),
        'order_status' => array('type' => 'string', 'length' => 255, 'index' => true),
        'sub_total'    => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'tax'          => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'order_total'  => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'currency'     => array('type' => 'string', 'length' => 255, 'index' => true),
        'cart_id'      => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => CartRepository::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'created'      => array('type' => 'integer', 'unsigned' => true),
        'modified'     => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * Order items
     */
    OrderItem::getTable()                 => array(
        'id'             => array('type' => 'primary', 'unsigned' => true),
        'order_id'       => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Order::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'orderable_id'   => array('type' => 'string', 'length' => 255, 'index' => true),
        'orderable_type' => array('type' => 'string', 'length' => 255, 'index' => true),
        'item_data'      => array('type' => 'string', 'length' => 16777215),
        'quantity'       => array('type' => 'integer', 'index' => true),
        'price'          => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'tax'            => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'currency'       => array('type' => 'string', 'length' => 255, 'index' => true),
    ),
    /**
     * Order user data (who placed the order)
     */
    OrderUser::getTable()                 => array(
        'id'       => array('type' => 'primary', 'unsigned' => true),
        'order_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Order::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'user_id'  => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'default'  => null,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'name'     => array('type' => 'string', 'length' => 255, 'index' => true),
        'value'    => array('type' => 'string', 'length' => 65535),
    ),
    // --------------------------------------------------------------
    // Subscription tables
    // --------------------------------------------------------------
    /**
     * Subscription categories
     */
    SubscriptionCategory::getTable()      => array(
        'id'          => array('type' => 'primary', 'unsigned' => true),
        'name'        => array('type' => 'string', 'length' => 255, 'index' => true),
        'interval'    => array('type' => 'integer', 'index' => true, 'unsigned' => true),
        'amount'      => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'status'      => array('type' => 'boolean', 'index' => true),
        'description' => array('type' => 'string', 'length' => 65535),
        'created'     => array('type' => 'integer', 'unsigned' => true),
        'modified'    => array('type' => 'integer', 'unsigned' => true),
    ),
    /**
     * User Subscriptions
     */
    Subscription::getTable()              => array(
        'id'                       => array('type' => 'primary', 'unsigned' => true),
        'subscription_category_id' => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => SubscriptionCategory::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'user_id'                  => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => User::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE CASCADE',
            ),
        ),
        'order_id'                 => array(
            'type'     => 'integer',
            'index'    => true,
            'unsigned' => true,
            'foreign'  => array(
                'table'   => Order::getTable(),
                'columns' => array('id'),
                'action'  => 'DELETE SET NULL',
            ),
        ),
        'name'                     => array('type' => 'string', 'length' => 255, 'index' => true),
        'interval'                 => array('type' => 'integer', 'index' => true, 'unsigned' => true),
        'amount'                   => array('type' => 'decimal', 'precision' => 10, 'scale' => 2),
        'status'                   => array('type' => 'boolean', 'index' => true),
        'description'              => array('type' => 'string', 'length' => 65535),
        'notifications'            => array('type' => 'string', 'length' => 16777215),
        'created'                  => array('type' => 'integer', 'unsigned' => true),
        'modified'                 => array('type' => 'integer', 'unsigned' => true),
        'starts'                   => array('type' => 'integer', 'unsigned' => true, 'index' => true),
        'expires'                  => array('type' => 'integer', 'unsigned' => true, 'index' => true),
    ),
    // --------------------------------------------------------------
    // Export
    // --------------------------------------------------------------
    Export::getTable() => array(
        'id'          => array('type' => 'primary', 'unsigned' => true),
        'exporter'    => array('type' => 'string', 'length' => 255, 'index' => true),
        'columns'     => array('type' => 'string', 'length' => 16777215),
        'name'        => array('type' => 'string', 'length' => 255, 'index' => true),
        'description' => array('type' => 'string', 'length' => 65535),
        'status'      => array('type' => 'boolean', 'index' => true),
        'created'     => array('type' => 'integer', 'unsigned' => true),
        'modified'    => array('type' => 'integer', 'unsigned' => true),
    ),
);
