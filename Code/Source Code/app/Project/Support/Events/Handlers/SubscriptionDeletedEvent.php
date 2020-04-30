<?php
/**
 * File: SubscriptionDeletedEvent.php
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

class SubscriptionDeletedEvent extends AbstractEventHandler
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
     */
    public function __construct()
    {
        $this->subscription = func_get_arg(0);

        $this->user = User::findOrFail($this->subscription->user_id);
        $this->profile = $this->user->profiles->load();

        // get the template and replace the needed parts
        $this->template = Template::one(array('type' => 'subscription', 'name' => 'deleted',));
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
            $subscription_name = h($this->subscription->name);
            if (!$this->subscription->isCurrent()) {
                $subscription_name = sprintf(_('upcoming %s'), $subscription_name);
            }
            $replacements = array(
                'date'                 => Carbon::now()->toDayDateTimeString(),
                'subscription_expires' => Carbon::createFromTimestamp(
                    $this->subscription->attributes['expires'],
                    config('timezone')
                )->toDayDateTimeString(),
                'subscription_name'    => $subscription_name,
                'user_name'            => $name,
            );

            // Replace the template message and subject
            list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);

            // Send email
            $this->mail($subject, $message, $email, $name);

            // Log
            $this->log(
                $this->subscription,
                'Subscription deleted for {user}.',
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
