<?php
/**
 * File: SubmissionAcceptedEvent.php
 * Created: 25-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Project\Models\Submission;
use Project\Models\Subscription;
use Project\Models\User;
use Story\Error;

class SubmissionAcceptedEvent extends AbstractEventHandler
{

    /**
     * @var User
     */
    protected $author;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var Submission
     */
    protected $submission;

    /**
     * @var User
     */
    protected $user;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {

        $this->submission = func_get_arg(0);
        $this->user = func_get_arg(1);
        $this->author = User::find($this->submission->user_id);
        $this->subject = func_get_arg(2);
        $this->message = func_get_arg(3);
    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {

        try {

            // Send email
            $this->mail(
                $this->subject,
                $this->message,
                $this->author->email,
                $this->author->profiles->load()->findBy('name', 'name', '')->value
            );

            // Add subscription if needed
            $this->addSubscription();

            // Log
            $this->log(
                $this->submission,
                '{user} {accept} the submission.',
                array(
                    'user'          => $this->user->id,
                    'user_fallback' => $this->user->profiles->load()->findBy('name', 'name', '')->value,
                    'accept'        => _('accepted'),
                )
            );
        } catch (\Exception $e) {
            Error::exception($e);
        }

    }

    /**
     * Adds a subscription if the user does not have one
     *
     * @return bool
     */
    private function addSubscription()
    {
        $category = post('subscription_category');

        if (!$category) {
            return false;
        }

        if (!Subscription::hasCurrent($this->author)) {
            // if no subscription found we add a free subscription
            $input = array(
                'category' => $category,
                'description' => sprintf('Free subscription for %s submission.', $this->submission->name)
            );
            Subscription::build($input, null, $this->author, '1');

            return true;
        }

        return false;
    }
}
