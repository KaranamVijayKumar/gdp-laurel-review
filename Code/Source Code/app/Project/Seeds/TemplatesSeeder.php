<?php
/**
 * File: TemplatesSeeder.php
 * Created: 17-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Project\Models\Newsletter;
use Project\Models\SubmissionStatus;
use Project\Models\Template;

class TemplatesSeeder
{
    /**
     * @var \Story\DB
     */
    public $db;


    public function run()
    {

        $table = Template::getTable();
        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i($table));

        // --------------------------------------------------------------
        // Submissions
        // --------------------------------------------------------------
        // submission created
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'submission',
                'name'    => 'created',
                'subject' => 'The Laurel Review: Submission',
                'message' => file_get_contents(__DIR__ . '/stubs/submission.created.html'),
                'description' => _('Email: Submission created'),
                'variables' => json_encode(
                    array(
                        '{submission_name}' => _('Submission name'),
                        '{date}' => _('Submission creation date'),
                        '{author_name}' => _('Submission author name'),
                        '{submission_url}' => _('Submission URL'),
                    )
                ),
            )
        );

        // accepted submission
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'submission',
                'name'    => SubmissionStatus::STATUS_ACCEPTED,
                'subject' => 'The Laurel Review: Submission',
                'message' => file_get_contents(__DIR__ . '/stubs/submission.accepted.html'),
                'description' => _('Email: Submission accepted'),
                'variables' => json_encode(
                    array(
                        '{submission_name}' => _('Submission name'),
                        '{author_name}' => _('Submission author name'),
                        '{sign_url}' => _('Sign URL (auto generated)'),
                    )
                ),
            )
        );

        // submission declined
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'submission',
                'name'    => SubmissionStatus::STATUS_DECLINED,
                'subject' => 'The Laurel Review: Submission',
                'message' => file_get_contents(__DIR__ . '/stubs/submission.declined.html'),
                'description' => _('Email: Submission declined'),
                'variables' => json_encode(
                    array(
                        '{submission_name}' => _('Submission name'),
                        '{author_name}' => _('Submission author name'),
                    )
                ),
            )
        );

        // Submission release form
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'submission',
                'name'    => 'sign',
                'subject' => 'Submission release form',
                'message' => file_get_contents(__DIR__ . '/stubs/submission.sign.html'),
                'description' => _('Release form: Submission release form contents.'),
                'variables' => json_encode(
                    array(
                        '{submission_name}' => _('Submission name'),
                        '{author_name}' => _('Submission author name'),
                        '{date}' => _('Submission creation date'),
                    )
                ),
            )
        );

        // --------------------------------------------------------------
        // Subscriptions
        // --------------------------------------------------------------
        // subscription created
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'subscription',
                'name'    => 'created',
                'subject' => 'The Laurel Review: Subscription created',
                'message' => file_get_contents(__DIR__ . '/stubs/subscription.created.html'),
                'description' => _('Email: Subscription created'),
                'variables' => json_encode(
                    array(
                        '{date}' => _('Subscription creation date'),
                        '{subscription_expires}' => _('Subscription expiration'),
                        '{subscription_name}' => _('Subscription name'),
                        '{subscription_url}' => _('Subscription URL'),
                        '{user_name}' => _('User name'),
                    )
                ),
            )
        );

        // subscription renewed
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'subscription',
                'name'    => 'renewed',
                'subject' => 'The Laurel Review: Subscription was renewed',
                'message' => file_get_contents(__DIR__ . '/stubs/subscription.renewed.html'),
                'description' => _('Email: Subscription renewed'),
                'variables' => json_encode(
                    array(
                        '{date}' => _('Subscription creation date'),
                        '{subscription_expires}' => _('Subscription expiration'),
                        '{subscription_name}' => _('Subscription name'),
                        '{subscription_url}' => _('Subscription URL'),
                        '{user_name}' => _('User name'),
                    )
                ),
            )
        );

        // subscription cancelled
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'subscription',
                'name'    => 'deleted',
                'subject' => 'The Laurel Review: Subscription cancelled',
                'message' => file_get_contents(__DIR__ . '/stubs/subscription.deleted.html'),
                'description' => _('Email: Subscription cancelled'),
                'variables' => json_encode(
                    array(
                        '{date}' => _('Subscription creation date'),
                        '{subscription_expires}' => _('Subscription expiration'),
                        '{subscription_name}' => _('Subscription name'),
                        '{user_name}' => _('User name'),
                    )
                ),
            )
        );

        // subscription is about to expire
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'subscription',
                'name'    => 'expires',
                'subject' => 'The Laurel Review: Subscription will expire in {day}',
                'message' => file_get_contents(__DIR__ . '/stubs/subscription.expires.html'),
                'description' => _('Email: Subscription expiration reminder'),
                'variables' => json_encode(
                    array(
                        '{day}'  => _('Expiration in days.'),
                        '{date}' => _('Subscription creation date'),
                        '{subscription_expires}' => _('Subscription expiration'),
                        '{subscription_name}' => _('Subscription name'),
                        '{renew_url}' => _('Subscription renewal URL'),
                        '{user_name}' => _('User name'),
                    )
                ),
            )
        );


        // --------------------------------------------------------------
        // Account
        // --------------------------------------------------------------
        // account created
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'account',
                'name'    => 'created',
                'subject' => 'The Laurel Review: Account Created',
                'message' => file_get_contents(__DIR__ . '/stubs/account.created.html'),
                'description' => _('Email: Account activation request'),
                'variables' => json_encode(
                    array(
                        '{user_name}' => _('User\'s name'),
                        '{date}' => _('When the change occured'),
                        '{activation_url}' => _('Activation URL.'),
                    )
                ),
            )
        );

        // account activated
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'account',
                'name'    => 'activated',
                'subject' => 'The Laurel Review: Welcome',
                'message' => file_get_contents(__DIR__ . '/stubs/account.activated.html'),
                'description' => _('Email: Account activated'),
                'variables' => json_encode(
                    array(
                        '{user_name}' => _('User\'s name'),
                        '{date}' => _('When the change occured'),
                        '{sign_in_url}' => _('Sign in URL'),
                    )
                ),
            )
        );

        // account updated
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'account',
                'name'    => 'updated',
                'subject' => 'The Laurel Review: Account Updated',
                'message' => file_get_contents(__DIR__ . '/stubs/account.updated.html'),
                'description' => _('Email: Account updated'),
                'variables' => json_encode(
                    array(
                        '{user_name}' => _('User\'s name'),
                        '{date}' => _('When the change occured'),
                        '{forgot_url}' => _('Forgot password URL.'),
                        '{details}' => _('Which part of the account was updated.'),
                    )
                ),
            )
        );

        // account forgot
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'account',
                'name'    => 'forgot',
                'subject' => 'The Laurel Review: Reset Password',
                'message' => file_get_contents(__DIR__ . '/stubs/account.forgot.html'),
                'description' => _('Email: Account Reset password'),
                'variables' => json_encode(
                    array(
                        '{user_name}' => _('User\'s name'),
                        '{date}' => _('When the request occured'),
                        '{reset_url}' => _('Reset password URL.'),
                    )
                ),
            )
        );

        // account deleted
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'account',
                'name'    => 'deleted',
                'subject' => 'The Laurel Review: Account deleted',
                'message' => file_get_contents(__DIR__ . '/stubs/account.deleted.html'),
                'description' => _('Email: Account Deleted'),
                'variables' => json_encode(
                    array(
                        '{name}' => _('User\'s name'),
                        '{date}' => _('When the request occured')
                    )
                ),
            )
        );

        // contact (user)
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'contact',
                'name'    => 'client',
                'subject' => 'The Laurel Review: Thank you for contacting us!',
                'message' => file_get_contents(__DIR__ . '/stubs/contact.client.html'),
                'description' => _('Email: Contact page contents received'),
                'variables' => json_encode(
                    array(
                        '{email}' => _('Visitor\'s email address'),
                        '{name}' => _('Visitor\'s name'),
                    )
                ),
            )
        );

        // contact (admin)
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'contact',
                'name'    => 'admin',
                'subject' => 'Message from {sender_name}',
                'message' => file_get_contents(__DIR__ . '/stubs/contact.admin.html'),
                'description' => _('Email: Contact page email for administrators'),
                'variables' => json_encode(
                    array(
                        '{sender_email}' => _('Visitor\'s email address'),
                        '{sender_name}' => _('Visitor\'s name'),
                        '{sender_ip}' => _('Visitor\'s IP address'),
                        '{sender_agent}' => _('Visitor\'s browser user agent'),
                        '{sent_on}' => _('Sent date'),
                        '{msg}' => _('Message'),
                    )
                ),
            )
        );

        // newsletter
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'newsletter',
                'name'    => Newsletter::TEMPLATE_NAME,
                'subject' => '{subject}',
                'message' => file_get_contents(__DIR__ . '/stubs/newsletter.' . Newsletter::TEMPLATE_NAME .'.html'),
                'description' => _('Email: Newsletter default template'),
                'variables' => json_encode(
                    array(
                        '{subject}' => _('Email subject'),
                        '{content}' => _('Newsletter content'),
                        '{unsubscribe}' => _('Newsletter unsubscribe link'),
                    )
                ),
            )
        );

        // newsletter subscribe confirmation
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'newsletter',
                'name'    => 'confirm_subscribe',
                'subject' => 'Confirm newsletter subscription',
                'message' => file_get_contents(__DIR__ . '/stubs/newsletter.confirm_subscribe.html'),
                'description' => _('Email: Newsletter subscribed confirmation'),
                'variables' => json_encode(
                    array(
                        '{subscribe}' => _('Newsletter confirmation link'),
                    )
                ),
            )
        );

        // newsletter unsubscribe confirmation
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'newsletter',
                'name'    => 'confirm_unsubscribe',
                'subject' => 'Confirm newsletter cancellation',
                'message' => file_get_contents(__DIR__ . '/stubs/newsletter.confirm_unsubscribe.html'),
                'description' => _('Email: Newsletter unsubscribe confirmation'),
                'variables' => json_encode(
                    array(
                        '{unsubscribe}' => _('Newsletter unsubscribe link'),
                    )
                ),
            )
        );

        // --------------------------------------------------------------
        // Orders
        // --------------------------------------------------------------
        // processed
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'order',
                'name'    => 'processed',
                'subject' => 'The Laurel Review: Your order is being processed',
                'message' => file_get_contents(__DIR__ . '/stubs/order.processed.html'),
                'description' => _('Email: Order processed'),
                'variables' => json_encode(
                    array(
                        '{name}' => _('Name'),
                        '{date}' => _('Order creation date'),
                        '{status}' => _('Order status'),
                        '{items}' => _('Ordered items'),
                        '{order_id}' => _('Order id'),
                    )
                ),
            )
        );

        // shipped
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'order',
                'name'    => 'shipped',
                'subject' => 'The Laurel Review: Your order has been shipped',
                'message' => file_get_contents(__DIR__ . '/stubs/order.shipped.html'),
                'description' => _('Email: Order shipped'),
                'variables' => json_encode(
                    array(
                        '{name}' => _('Name'),
                        '{date}' => _('When the order was shipped'),
                        '{status}' => _('Order status'),
                        '{items}' => _('Ordered items'),
                        '{order_id}' => _('Order id'),
                    )
                ),
            )
        );

        // complete
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'order',
                'name'    => 'complete',
                'subject' => 'The Laurel Review: Thank you for your order',
                'message' => file_get_contents(__DIR__ . '/stubs/order.complete.html'),
                'description' => _('Email: Order complete'),
                'variables' => json_encode(
                    array(
                        '{name}' => _('Name'),
                        '{date}' => _('Order creation date'),
                        '{status}' => _('Order status'),
                        '{items}' => _('Ordered items'),
                        '{order_id}' => _('Order id'),
                    )
                ),
            )
        );

        // order refunded
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'order',
                'name'    => 'refunded',
                'subject' => 'The Laurel Review: Your order was refunded',
                'message' => file_get_contents(__DIR__ . '/stubs/order.refunded.html'),
                'description' => _('Email: Order refunded'),
                'variables' => json_encode(
                    array(
                        '{name}' => _('Name'),
                        '{date}' => _('When the order was refunded'),
                        '{status}' => _('Order status'),
                        '{items}' => _('Ordered items'),
                        '{order_id}' => _('Order id'),
                    )
                ),
            )
        );

        // order voided
        $this->db->insert(
            $table,
            array(
                'locked'  => 1,
                'type'    => 'order',
                'name'    => 'voided',
                'subject' => 'The Laurel Review: Your order was cancelled',
                'message' => file_get_contents(__DIR__ . '/stubs/order.voided.html'),
                'description' => _('Email: Order cancelled/voided'),
                'variables' => json_encode(
                    array(
                        '{name}' => _('Name'),
                        '{date}' => _('When the order was voided'),
                        '{status}' => _('Order status'),
                        '{items}' => _('Ordered items'),
                        '{order_id}' => _('Order id'),
                    )
                ),
            )
        );

    }
}
