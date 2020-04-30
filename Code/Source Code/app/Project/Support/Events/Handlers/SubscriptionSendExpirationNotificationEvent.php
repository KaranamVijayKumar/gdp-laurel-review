<?php
/**
 * File: SubscriptionSendExpirationNotificationEvent.php
 * Created: 24-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Carbon\Carbon;
use Project\Models\Template;
use Project\Models\Subscription;
use Project\Models\User;
use Story\Error;

/**
 * Class SubscriptionSendExpirationNotificationEvent
 *
 * @package Project\Support\Events\Handlers
 */
class SubscriptionSendExpirationNotificationEvent extends AbstractEventHandler
{

    /**
     * @var int
     */
    protected $expiration_day;

    /**
     * @var \Story\Collection
     */
    protected $profile;

    /**
     * @var Template
     */
    protected $template;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Subscription
     */
    private $subscription;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {
        $this->subscription = func_get_arg(0);
        $this->template = func_get_arg(1);
        $this->expiration_day = func_get_arg(2);

        $this->user = $this->subscription->user;
    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        try {

            $subscription_name = h($this->subscription->name);
            $days = $this->subscription->daysTillExpire();

            if ($days > 0) {
                $days = sprintf(ngettext('%1$d day', '%1$d days', $days), $days);
            } else {
                $days = _('today');
            }

            $replacements = array(
                'date'                 => Carbon::now()->toDayDateTimeString(),
                'subscription_expires' => $this->subscription->expires->toDayDateTimeString(),
                'subscription_name'    => $subscription_name,
                'renew_url'            => action('\Project\Controllers\Subscriptions\Create', array('renew',)),
                'user_name'            => $this->user->user_name ?: $this->user->email,
                'day'                  => $days,
            );

            // Replace the template message and subject
            list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);

            // Send email
            $this->mail($subject, $message, $this->user->email, $this->user->user_name ?: '');

            // Update the subscription with the new notifications
            $this->updateSubscription();

            // Log
            $this->log(
                $this->subscription,
                'Subscription expiration notification sent for user for {user} for {subscription}.',
                array(
                    'user'                  => $this->user->id,
                    'user_fallback'         => $this->user->user_name,
                    'subscription'          => $subscription_name,
                    'subscription_fallback' => $this->subscription->id,
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Updates the subscription
     *
     * @return Subscription
     */
    private function updateSubscription()
    {
        // save the notification for the subscription
        $this->subscription->notifications = array_replace_recursive(
            (array) $this->subscription->notifications,
            array('expire' => array((string) $this->expiration_day => time(),),)
        );

        return $this->subscription->save();
    }
}
