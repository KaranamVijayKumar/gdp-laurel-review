<?php
/*!
 * newsletter_events.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

/*
|--------------------------------------------------------------------------
| newsletter sent
|--------------------------------------------------------------------------
|
| This event is fired when a newsletter is sent to all the subscribers
|
*/

event('newsletter.sent', null, 'Project\Support\Events\Handlers\NewsletterSentEvent');

/*
|--------------------------------------------------------------------------
| Subscription confirmation
|--------------------------------------------------------------------------
|
|
*/

event(
    'newsletter.subscribe_confirmation',
    null,
    'Project\Support\Events\Handlers\NewsletterSubscribeConfirmationEvent'
);

/*
|--------------------------------------------------------------------------
| Unsubscription confirmation
|--------------------------------------------------------------------------
|
|
*/

event(
    'newsletter.unsubscribe_confirmation',
    null,
    'Project\Support\Events\Handlers\NewsletterUnsubscribeConfirmationEvent'
);


