<?php
/**
 * File: subscription_events.php
 * Created: 05-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

/*
|--------------------------------------------------------------------------
| Subscription created
|--------------------------------------------------------------------------
|
| This event is fired when a subscription was created
|
*/

use Project\Models\Log;

event('subscription.created', null, 'Project\Support\Events\Handlers\SubscriptionCreatedEvent');


/*
|--------------------------------------------------------------------------
| Subscription created (inactive)
|--------------------------------------------------------------------------
|
| This event is fired when an inactive subscription was created
|
*/
event(
    'subscription.created.inactive',
    null,
    function ($subscription) {


    }
);
/*
|--------------------------------------------------------------------------
| Subscription updated
|--------------------------------------------------------------------------
|
| This event is fired when a subscription was updated
|
*/
event(
    'subscription.updated',
    null,
    function ($subscription) {

        Log::create(
            $subscription,
            'Subscription updated.',
            array()
        );
    }
);

/*
|--------------------------------------------------------------------------
| Subscription updated (inactive)
|--------------------------------------------------------------------------
|
| This event is fired when a subscription was updated
|
*/
event(
    'subscription.updated.inactive',
    null,
    function ($subscription) {

        Log::create(
            $subscription,
            'Subscription updated.',
            array()
        );
    }
);

/*
|--------------------------------------------------------------------------
| Subscription expired
|--------------------------------------------------------------------------
|
| This event is fired when a subscription expired
|
*/
event(
    'subscription.expired',
    null,
    function ($args) {

    }
);

/*
|--------------------------------------------------------------------------
| Subscription renewed
|--------------------------------------------------------------------------
|
| This event is fired when a subscription is renewed
|
*/
event('subscription.renewed', null, 'Project\Support\Events\Handlers\SubscriptionRenewedEvent');

/*
|--------------------------------------------------------------------------
| Subscription renewed (inactive)
|--------------------------------------------------------------------------
|
| This event is fired when an inactive subscription is renewed
|
*/
event(
    'subscription.renewed.inactive',
    null,
    function ($args) {

    }
);

/*
|--------------------------------------------------------------------------
| Subscription deleted
|--------------------------------------------------------------------------
|
| This event is fired when a subscription was cancelled
|
*/

event('subscription.deleted', null, 'Project\Support\Events\Handlers\SubscriptionDeletedEvent');


/*
|--------------------------------------------------------------------------
| Subscription category created
|--------------------------------------------------------------------------
|
| This event is fired when a subscription category was created
|
*/
event(
    'subscription.category.created',
    null,
    function ($args) {

    }
);

/*
|--------------------------------------------------------------------------
| Subscription category deleted
|--------------------------------------------------------------------------
|
| This event is fired when a subscription category was deleted
|
*/
event(
    'subscription.category.deleted',
    null,
    function ($args) {

    }
);

/*
|--------------------------------------------------------------------------
| Notification for user about subscription expiration
|--------------------------------------------------------------------------
|
| Notifies the user that his expiration is about to expire
|
*/

event(
    'subscription.send_expiration_notification',
    null,
    'Project\Support\Events\Handlers\SubscriptionSendExpirationNotificationEvent'
);
