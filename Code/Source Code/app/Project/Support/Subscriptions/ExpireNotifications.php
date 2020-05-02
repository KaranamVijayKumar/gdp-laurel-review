<?php
/**
 * File: ExpireNotifications.php
 * Created: 23-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Subscriptions;

use Project\Models\Template;
use Project\Models\Subscription;
use Project\Models\User;
use Story\Collection;

/**
 * Class ExpireNotifications
 *
 * @package Project\Support\Subscriptions
 */
class ExpireNotifications
{

    /**
     * Max email batch size.
     *
     * @var int
     */
    protected $batch_size = 50;

    /**
     * Notification days (default: 3, 19)
     *
     * @var array
     */
    protected $notification_days = array(3, 19,);

    /**
     * Constructor
     *
     * @param string $database
     */
    public function __construct($database = 'database')
    {

        $this->repository = load_database($database);
        $this->notification_days = (array)config('subscription_renew_notify_days', $this->notification_days);
        require_once SP . 'Project/Support/Events/subscription_events.php';
    }

    /**
     * @return array
     */
    public function getNotificationDays()
    {

        return $this->notification_days;
    }

    /**
     * Sets the notification days
     *
     * @param array $notification_days
     */
    public function setNotificationDays($notification_days)
    {

        $this->notification_days = $notification_days;
    }

    /**
     * Sends the notifications
     *
     */
    public function send()
    {

        $renewables = $this->getRenewables();

        // get the template and replace the needed parts
        $template = Template::one(array('type' => 'subscription', 'name' => 'expires',));

        foreach ($this->notification_days as $notification_day) {
            $this->sendNotificationsByDay($renewables, $notification_day, $template);
        }

        print colorize("Finished.\n", 'green');
    }

    /**
     * Return the renewable subscriptions
     *
     * @return Collection
     */
    protected function getRenewables()
    {

        /** @var Collection $currents */
        $currents = Subscription::getAllCurrent(array('status' => '1',));

        /** @var Collection $upcoming */
        $upcoming = Subscription::getAllUpcoming(array('status' => '1',));

        $renewableCollection = new Collection;
        /** @var Subscription $current */
        foreach ($currents as $current) {
            // we check if in the upcoming we have the same user

            if ($current->canRenew() && !$upcoming->findBy('user_id', $current->user_id)) {
                $renewableCollection->push($current);
            }

        }

        return $renewableCollection;
    }

    /**
     * @param Collection    $renewables
     * @param int           $notification_day
     * @param Template $template
     */
    private function sendNotificationsByDay(Collection $renewables, $notification_day, Template $template)
    {

        // find all the subscriptions by notification point
        // Once found, we remove it from the $renewables to prevent sending the notifications more then one time
        $collection = new Collection;


        /** @var Subscription $subscription */
        foreach ($renewables as $id => $subscription) {
            $notifications = $subscription->notifications;

            // if no notifications, add the expire key to the notifications
            if (!array_key_exists('expire', (array) $notifications)) {
                $notifications['expire'] = array();
            }

            // If days till expire matches the notification day and not yet sent, we send the email
            if (!array_key_exists((string)$notification_day, $notifications['expire'])
                && $notification_day == $subscription->daysTillExpire()
            ) {

                $collection->push($subscription);
                $renewables->forgetByIndex($id);
            }
        }

        $this->sendEmails($collection, $notification_day, $template);
    }

    /**
     * @param Collection    $collection
     * @param int           $notification_day
     * @param Template $template
     *
     * @return bool
     */
    private function sendEmails(Collection $collection, $notification_day, Template $template)
    {

        // get the users for the subscriptions
        $user_ids = array();

        foreach ($collection as $subscription) {
            $user_ids[] = $subscription->user_id;
        }

        if (!count($user_ids)) {
            print colorize(sprintf("Nobody to notify on day %s.\n", colorize($notification_day, 'yellow')), 'blue');
            return false;
        } else {
            print colorize('Day ' . $notification_day . ":\n", 'yellow');
        }

        // get the users
        $users = User::getAllUsersByIds(array_unique($user_ids));


        $subscriptions = new Collection;
        // do we need batch?
        if ($this->batch_size <= count($collection)) {
            for ($i = 0; $i < $this->batch_size; ++$i) {
                /** @var Subscription $subscription */
                $subscription = $collection->getByIndex($i);
                $subscription->user = $users->findBy('id', $subscription->user_id);
                $subscriptions->push($subscription);
            }

        } else {
            foreach ($collection as $subscription) {
                $subscription->user = $users->findBy('id', $subscription->user_id);
                $subscriptions->push($subscription);
            }
        }


        // Send the notifications to the subscriptions' users
        foreach ($subscriptions as $subscription) {
            $email = $subscription->user->email;
            if (event(
                'subscription.send_expiration_notification',
                array($subscription, $template, $notification_day,)
            )) {
                print colorize("  ✔ " . $email . "\n", 'yellow');
            } else {
                break;
            }
        }

        return true;
    }
}
