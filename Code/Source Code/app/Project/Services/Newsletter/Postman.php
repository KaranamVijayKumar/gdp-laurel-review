<?php
/**
 * File: Postman.php
 * Created: 07-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Newsletter;

use Html2Text\Html2Text;
use Project\Models\Newsletter;
use Project\Models\NewsletterSubscriber;
use Project\Models\NewsletterSubscriberPivot;
use Project\Models\Template;
use Project\Services\Mailer;
use Story\Cipher;
use Story\HTML;

/**
 * Class Postman
 * @package Project\Services\Newsletter
 */
class Postman
{

    /**
     * DB template cache
     *
     * @var array
     */
    public static $template_cache = array();

    /**
     * How many emails do we send in one run (default is 50)
     * @var int
     */
    public $batch_size = 250;

    /**
     * Sends a specific newsletter
     *
     * @param Newsletter $newsletter
     * @return boolean
     */
    public function send(Newsletter $newsletter)
    {
        // Check if we have already the template loaded
        if (!array_key_exists($newsletter->template_id, static::$template_cache)) {
            static::$template_cache[$newsletter->template_id] = Template::one(array('id' => $newsletter->template_id));
        }

        // If we dont have template, we return false
        if (!static::$template_cache[$newsletter->template_id]) {
            return false;
        }

        $message = $this->replaceVars($newsletter, static::$template_cache[$newsletter->template_id]);

        // get all the users who didn't get this newsletter
        $email_addresses = $newsletter->unsentToSubscribers();

        // Do we have addresses? if not, we set the flag that this newsletter was sent and return true
        if (!count($email_addresses)) {
            if (php_sapi_name() == 'cli') {
                print colorize("  ✔ Newsletter \"{$newsletter->content->first()->subject}\" complete.\n", 'yellow');
            }
            $newsletter->sent();

            return true;
        }

        // We send to each user by batch
        $sent_subscriber_ids = array();
        if ($this->batch_size <= count($email_addresses)) {
            for ($i = 0; $i < $this->batch_size; ++$i) {
                $sent_subscriber_ids[] = $this->sendMail($message, $email_addresses[$i]);
            }
        } else {
            foreach ($email_addresses as $subscriber) {
                $sent_subscriber_ids[] = $this->sendMail($message, $subscriber);
            }
        }

        // filter out the false results
        $sent_subscriber_ids = array_filter($sent_subscriber_ids);

        // and insert to the pivot the newsletter along with the subscriber ids
        if (count($sent_subscriber_ids)) {
            NewsletterSubscriberPivot::insertFor($newsletter, $sent_subscriber_ids);

        }

        $count = count($sent_subscriber_ids);

        if (php_sapi_name() == 'cli') {
            print colorize(
                "\n  ✔ Newsletter \"{$newsletter->content->first()->subject}\" sent for {$count} subscribers.\n",
                'yellow'
            );
        }

        return true;
    }

    /**
     * Sends all unsent newsletters
     *
     */
    public function sendAll()
    {
        // find the newsletter that were not sent
        $items = Newsletter::all(
            array(
                'status' => '1',
                Newsletter::$db->i('sent') . ' IS NULL'
            )
        );


        if (count($items)) {
            foreach ($items as $k => $item) {
                $items[$k] = new Newsletter($item);
            }
            foreach ($items as $item) {
                $this->send($item);
            }

        } else {
            if (php_sapi_name() == 'cli') {
                print colorize("  No newsletter to send.\n", 'yellow');
            }
        }

        return count($items);
    }

    /**
     * Sets the batch size
     * @param int $batch_size
     */
    public function setBatchSize($batch_size)
    {
        $this->batch_size = $batch_size;
    }

    /**
     * Replaces the template vars and returns a new \stdClass
     * @param $newsletter
     * @param $template
     * @return \stdClass
     */
    protected function replaceVars($newsletter, $template)
    {
        // replace the content for the template
        $newsletter->content->load();

        $replace = array(
            '{subject}' => $newsletter->content->first()->subject,
            '{content}' => $newsletter->content->first()->content,
        );

        $message = new \stdClass();
        foreach (array('message', 'subject') as $name) {
            $message->$name = str_replace(
                array_keys($replace),
                array_values($replace),
                $template->$name
            );
        }

        return $message;
    }

    /**
     * Sends the newsletter to the user
     *
     * @param \stdClass $message
     * @param \stdClass $subscriber
     * @return bool
     */
    protected function sendMail(\stdClass $message, \stdClass $subscriber)
    {

        $unsubscribe = NewsletterSubscriber::encodeAsActionFromId(
            $subscriber->id,
            '\Project\Controllers\Newsletter\Unsubscribe'
        );

        $unsubscribe = HTML::link($unsubscribe, _('Unsubscribe'));


        // replace the unsubscribe in the message
        $message->message = str_replace('{unsubscribe}', $unsubscribe, $message->message);

        $text = new Html2Text($message->message);
        $message->text = $text->getText();

        // send the email
        $result = Mailer::sendMail(
            function ($mail) use ($message, $subscriber) {

                /** @var Mailer $mail */
                /** @var Template $template */
                $mail->Subject = $message->subject;
                $mail->Body = $message->message;
                $mail->AltBody = trim($message->text);

                $mail->addAddress($subscriber->email);
            }
        );


        if ($result) {
            if (php_sapi_name() == 'cli') {
                print colorize(".", 'green');
            }

            return $subscriber->id;
        }

        return false;
    }
}
