<?php
/**
 * File: submission_events.php
 * Created: 23-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

// submission created
use Html2Text\Html2Text;
use Project\Models\Template;
use Project\Models\Log;
use Project\Models\Submission;
use Project\Models\SubmissionStatus;
use Project\Models\User;
use Project\Services\Mailer;
use Story\Collection;

/*
|--------------------------------------------------------------------------
| Submission created
|--------------------------------------------------------------------------
|
| Logs the creation of a new submission
|
*/

event(
    'submission.created',
    null,
    function ($args) {

        $submission = $args[0];
        /** @var User $author */
        $author = User::find($submission->user_id);

        /** @var Collection $profile */
        $profile = $author->profiles->load();

        load_database();

        // set the notification for the site
        /** @var \Story\Session $session */
        if (($session = app('session'))) {
            $session->flash('notice', 'Submission created successfully.');

        }

        // remove the items from the cart
//        Submission::removeFromCart();

        // Send notification email
        try {
            // get the template and replace the needed parts
            /** @var Template $template */
            $template = Template::one(array('type' => 'submission', 'name' => 'created',));

            $replace = array(
                '{author_name}'     => $profile->findBy('name', 'name')->value,
                '{submission_name}' => h($submission->name),
                '{date}' => \Carbon\Carbon::now()->toDayDateTimeString(),
                '{submission_url}' => action('\Project\Controllers\Submissions\Show', array($submission->id,)),
            );

            $template->message = str_replace(array_keys($replace), array_values($replace), $template->message);

            // send the email
            Mailer::sendMail(
                function ($mail) use ($template, $author, $profile) {

                    /** @var Mailer $mail */
                    /** @var Template $template */
                    $mail->Subject = $template->subject;
                    $mail->Body = $template->message;

                    $text = new Html2Text($template->message);
                    $mail->AltBody = trim($text->getText());

                    $mail->addAddress($author->email, $profile->findBy('name', 'name')->value ?: '');

                }
            );
        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }

        Log::create(
            $submission,
            '{user} created the submission.',
            array(
                'user'          => $author->id,
                'user_fallback' => $profile->findBy('name', 'name')->value,
            )
        );
    }
);

/*
|--------------------------------------------------------------------------
| Temporary submission creation
|--------------------------------------------------------------------------
|
*/

event(
    'submission.tmp_created',
    null,
    function ($args) {
        //
    }
);

/*
|--------------------------------------------------------------------------
| Submission signed
|--------------------------------------------------------------------------
|
| Submission signed by the user
|
*/

event(
    'submission.signed',
    null,
    function ($args) {

        $submission = $args[0];
        $author = $args[1];

        Log::create(
            $submission,
            '{user} signed the submission.',
            array(
                'user'          => $author->id,
                'user_fallback' => $author->profiles->load()->findBy('name', 'name')->value,
            )
        );
    }
);

event(
    'submission.withdrawn',
    null,
    function ($args) {

        $submission = $args[0];
        $author = $args[1];

        Log::create(
            $submission,
            '{user} withdrew the submission.',
            array(
                'user'          => $author->id,
                'user_fallback' => $author->profiles->load()->findBy('name', 'name')->value,
            )
        );
    }
);

event(
    'submission.withdrawn.partially',
    null,
    function ($args) {

        $submission = $args[0];
        $author = $args[1];

        Log::create(
            $submission,
            '{user} withdrew a part of the submission.',
            array(
                'user'          => $author->id,
                'user_fallback' => $author->profiles->load()->findBy('name', 'name')->value,
            )
        );
    }
);
/*
|--------------------------------------------------------------------------
| Submission updated
|--------------------------------------------------------------------------
|
| Logs the update of a new submission
|
*/

event(
    'submission.updated',
    null,
    function ($args) {

        $submission = $args[0];
        $user = $args[1];

        Log::create(
            $submission,
            '{user} updated the submission properties.',
            array(
                'user'          => $user->id,
                'user_fallback' => $user->profiles->load()->findBy('name', 'name')->value,
            )
        );
    }
);

/*
|--------------------------------------------------------------------------
| Submission like/dislike
|--------------------------------------------------------------------------
|
| Logs the if submission was liked/disliked
|
*/

event(
    'submission.updated.like',
    null,
    function ($args) {

        /** @var Submission $submission */
        $submission = $args[0];

        /** @var User $user */
        $user = $args[1];
        $value = $args[2] === 1 ? 'liked' : 'disliked';

        // update the submission to in progress if new
        /** @var SubmissionStatus $status */
        $status = SubmissionStatus::one(array('id' => $submission->submission_status_id,));
        /** @var SubmissionStatus $in_progress */
        $in_progress = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_PROGRESS,));

        if ($status && $in_progress && $status->slug === SubmissionStatus::STATUS_NEW) {
            $submission->submission_status_id = $in_progress->id;
            $submission->save();
        }

        Log::create(
            $submission,
            '{user} {like} the submission.',
            array(
                'user'          => $user->id,
                'user_fallback' => $user->profiles->load()->findBy('name', 'name')->value,
                'like'          => $value,
            )
        );
    }
);

/*
|--------------------------------------------------------------------------
| Submission accept
|--------------------------------------------------------------------------
|
| Logs and sends an email when the submission was accepted
|
*/
event('submission.accepted', null, 'Project\Support\Events\Handlers\SubmissionAcceptedEvent');

/*
|--------------------------------------------------------------------------
| Submission declined
|--------------------------------------------------------------------------
|
| Logs and sends an email when the submission was declined
|
*/
event(
    'submission.declined',
    null,
    function ($args) {

        /** @var Submission $submission */
        $submission = $args[0];
        /** @var User $user */
        $user = $args[1];
        /** @var User $user */
        $author = User::find($submission->user_id);

        $subject = $args[2];
        $message = $args[3];

        try {
            \Project\Services\Mailer::sendMail(
                function ($mail) use ($subject, $message, $author) {

                    /** @var \Project\Services\Mailer $mail */
                    $mail->Subject = $subject;
                    $mail->Body = $message;
                    $text = new \Html2Text\Html2Text($message);
                    $mail->AltBody = trim($text->getText());
                    $mail->addAddress($author->email, $author->profiles->load()->findBy('name', 'name')->value ?: '');
                }
            );
        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }

        // create the log
        Log::create(
            $submission,
            '{user} {accept} the submission.',
            array(
                'user'          => $user->id,
                'user_fallback' => $user->profiles->load()->findBy('name', 'name')->value,
                'accept'        => _('declined'),
            )
        );
    }
);

/*
|--------------------------------------------------------------------------
| Submission email
|--------------------------------------------------------------------------
|
| Sends an email to the submission's author
|
*/

event(
    'submission.email.created',
    null,
    function ($args) {
        /** @var Submission $submission */
        $submission = $args[0];
        /** @var \Project\Models\SubmissionEmail $user */
        $email = $args[1];
        /** @var User $user */
        $author = User::find($submission->user_id);
        /** @var User $user */
        $user = $args[2];

        try {
            \Project\Services\Mailer::sendMail(
                function ($mail) use ($email, $author) {

                    /** @var \Project\Services\Mailer $mail */
                    $mail->Subject = $email->subject;
                    $mail->Body = $email->message;
                    $text = new \Html2Text\Html2Text($email->message);
                    $mail->AltBody = trim($text->getText());
                    $mail->addAddress($author->email, $author->profiles->load()->findBy('name', 'name')->value ?: '');
                }
            );
        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }

        // create the log
        Log::create(
            $submission,
            '{user} sent an email to the author.',
            array(
                'user'          => $user->id,
                'user_fallback' => $user->profiles->load()->findBy('name', 'name')->value,
            )
        );
    }
);
