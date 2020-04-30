<?php
/**
 * File: SubscriptionRenewedEvent.php
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
 * Class SubscriptionRenewedEvent
 *
 * @package Project\Support\Events\Handlers
 */
class SubscriptionRenewedEvent extends AbstractEventHandler
{

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
     * @throws \Story\NotFoundException
     */
    public function __construct()
    {

        $this->subscription = func_get_arg(0);

        $this->user = User::findOrFail($this->subscription->user_id);
        $this->profile = $this->user->profiles->load();

        // get the template and replace the needed parts
        $this->template = Template::one(array('type' => 'subscription', 'name' => 'renewed',));
    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {

        try {

            $email = $this->user->email;
            $name = $this->profile->findBy('name', 'name', '')->value;

            $replacements = array(
                'date'                 => Carbon::now()->toDayDateTimeString(),
                'subscription_expires' => Carbon::createFromTimestamp(
                    $this->subscription->attributes['expires'],
                    config('timezone')
                )->toDayDateTimeString(),
                'subscription_name'    => h($this->subscription->name),
                'subscription_url'     => action('\Project\Controllers\Subscriptions\Index'),
                'user_name'            => $this->profile->findBy('name', 'name')->value,
            );

            // Replace the template message and subject
            list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);

            // Send email
            $this->mail($subject, $message, $email, $name);

            // Log
            $this->log(
                $this->subscription,
                'Subscription renewed for {user}.',
                array(
                    'user'          => $this->user->id,
                    'user_fallback' => $name,
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
