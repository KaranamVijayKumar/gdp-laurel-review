<?php
/**
 * File: account_events.php
 * Created: 30-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

use Html2Text\Html2Text;
use Project\Models\Template;
use Project\Models\Log;
use Project\Services\Mailer;
use Story\Auth;
use Story\Collection;

event(
    'account.forgot',
    null,
    function (\Project\Models\User $user) {
        // get the profile of the user
        /** @var Collection $profile */
        $profile = $user->profiles->load();

        try {


            // get the template and replace the needed parts
            /** @var Template $template */
            $template = Template::one(array('type' => 'account', 'name' => 'forgot',));

            if (isset($user->admin_route) && $user->admin_route) {
                $reset_url = action('\Project\Controllers\Admin\Account\ResetPassword', array($user->reminder->token,));
            } else {
                $reset_url = action('\Project\Controllers\Account\ResetPassword', array($user->reminder->token,));
            }
            $replace = array(
                '{user_name}'     => $profile->findBy('name', 'name')->value,
                '{date}' => \Carbon\Carbon::now()->toDayDateTimeString(),
                '{reset_url}' => $reset_url,

            );

            $template->message = str_replace(array_keys($replace), array_values($replace), $template->message);

            // send the email
            Mailer::sendMail(
                function ($mail) use ($template, $user, $profile) {

                    /** @var Mailer $mail */
                    /** @var Template $template */
                    $mail->Subject = $template->subject;
                    $mail->Body = $template->message;

                    $text = new Html2Text($template->message);
                    $mail->AltBody = trim($text->getText());

                    $mail->addAddress($user->email, $profile->findBy('name', 'name')->value ?: '');

                }
            );

        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }

        // log the action
        Log::create(
            $user,
            '{user} requested a password reminder.',
            array(
                'user'          => $user->id,
                'user_fallback' => $profile->findBy('name', 'name')->value,
                'author' => $user->id,
            )
        );
    }
);

// --------------------------------------------------------------
// New account with activation
// --------------------------------------------------------------
event(
    'account.created_inactive',
    null,
    function (\Project\Models\User $user) {

        // get the profile of the user
        /** @var Collection $profile */
        $profile = $user->profiles->load();

        try {

            // get the template and replace the needed parts
            /** @var Template $template */
            $template = Template::one(array('type' => 'account', 'name' => 'created',));

            $replace = array(
                '{user_name}'     => $profile->findBy('name', 'name')->value,
                '{date}' => \Carbon\Carbon::now()->toDayDateTimeString(),
                '{activation_url}' => action('\Project\Controllers\Account\Activate', array($user->activation_token,)),

            );

            $template->message = str_replace(array_keys($replace), array_values($replace), $template->message);

            // send the email
            Mailer::sendMail(
                function ($mail) use ($template, $user, $profile) {

                    /** @var Mailer $mail */
                    /** @var Template $template */
                    $mail->Subject = $template->subject;
                    $mail->Body = $template->message;

                    $text = new Html2Text($template->message);
                    $mail->AltBody = trim($text->getText());

                    $mail->addAddress($user->email, $profile->findBy('name', 'name')->value ?: '');

                }
            );

        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }

        // log the action
        Log::create(
            $user,
            '{user} created.',
            array(
                'user'          => $user->id,
                'user_fallback' => $profile->findBy('name', 'name')->value,
                'author' => $user->id,
            )
        );
    }
);

// --------------------------------------------------------------
// New account without activation
// --------------------------------------------------------------
event(
    'account.created_active',
    null,
    function (\Project\Models\User $user) {

        // get the profile of the user
        /** @var Collection $profile */
        $profile = $user->profiles->load();

        try {

            // get the template and replace the needed parts
            /** @var Template $template */
            $template = Template::one(array('type' => 'account', 'name' => 'activated',));

            $replace = array(
                '{user_name}'     => $profile->findBy('name', 'name')->value,
                '{date}' => \Carbon\Carbon::now()->toDayDateTimeString(),
                '{sign_in_url}' => action('\Project\Controllers\Auth'),

            );

            $template->message = str_replace(array_keys($replace), array_values($replace), $template->message);

            // send the email
            Mailer::sendMail(
                function ($mail) use ($template, $user, $profile) {

                    /** @var Mailer $mail */
                    /** @var Template $template */
                    $mail->Subject = $template->subject;
                    $mail->Body = $template->message;

                    $text = new Html2Text($template->message);
                    $mail->AltBody = trim($text->getText());

                    $mail->addAddress($user->email, $profile->findBy('name', 'name')->value ?: '');

                }
            );

        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }

        // log the action
        Log::create(
            $user,
            '{user} activated.',
            array(
                'user'          => $user->id,
                'user_fallback' => $profile->findBy('name', 'name')->value,
                'author' => $user->id,
            )
        );
    }
);

// --------------------------------------------------------------
// Account updated
// --------------------------------------------------------------
event(
    'account.updated',
    null,
    function ($args) {

        $user = $args[0];
        $message = $args[1];

        /** @var Collection $profile */
        $profile = $user->profiles->load();

        // Send notification email
        try {
            // get the template and replace the needed parts
            /** @var Template $template */
            $template = Template::one(array('type' => 'account', 'name' => 'updated',));

            $replace = array(
                '{user_name}'     => $profile->findBy('name', 'name')->value,
                '{date}' => \Carbon\Carbon::now()->toDayDateTimeString(),
                '{forgot_url}' => action('\Project\Controllers\Account\Forgot'),
                '{details}' => h($message),
            );

            $template->message = str_replace(array_keys($replace), array_values($replace), $template->message);

            // send the email
            Mailer::sendMail(
                function ($mail) use ($template, $user, $profile) {

                    /** @var Mailer $mail */
                    /** @var Template $template */
                    $mail->Subject = $template->subject;
                    $mail->Body = $template->message;

                    $text = new Html2Text($template->message);
                    $mail->AltBody = trim($text->getText());

                    $mail->addAddress($user->email, $profile->findBy('name', 'name')->value ?: '');
                    // if the user has original email we send it there as well
                    if (isset($user->original_email)) {
                        $mail->addBCC($user->original_email, $profile->findBy('name', 'name')->value ?: '');
                    }
                }
            );
        } catch (\Exception $e) {
            if (config('debug')) {
                \Story\Error::exception($e);
            }
            log_message($e->getMessage());
        }


        // log the action
        Log::create(
            $user,
            '{user} updated: ' . $message,
            array(
                'user'          => $user->id,
                'user_fallback' => $profile->findBy('name', 'name')->value,
                'author' => Auth::check() ? \Story\Auth::user()->id : $user->id,
            )
        );

    }
);

/*
|--------------------------------------------------------------------------
| Account deleted
|--------------------------------------------------------------------------
|
| This event is fired when an account is deleted
|
*/

event('account.deleted', null, 'Project\Support\Events\Handlers\AccountDeletedEvent');
