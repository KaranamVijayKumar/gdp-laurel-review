<?php
/**
 * File: AbstractEventHandler.php
 * Created: 24-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Html2Text\Html2Text;
use Project\Models\Template;
use Project\Models\Log;
use Project\Services\Mailer;

/**
 * Class AbstractEventHandler
 *
 * @package Project\Support\Events\Handlers
 */
abstract class AbstractEventHandler
{

    /**
     * These quotes will be replaced in mail
     *
     * @var array
     */
    public $quotation_replacements = array(
        '“' => '&ldquo;',
        '”' => '&rdquo;',
        '‘' => '&lsquo;',
        '’' => '&rsquo;',
    );
    /**
     * Event handler constructor
     *
     */
    abstract public function __construct();

    /**
     * Required to run this event
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * Creates a new log and saves it
     *
     * @param \stdClass|\Story\ORM $model
     * @param string               $message
     * @param mixed                $payload
     *
     * @return mixed
     */
    protected function log($model, $message, $payload)
    {

        return Log::create($model, $message, $payload);
    }

    /**
     * Sends a simple email message
     *
     * @param string $subject
     * @param string $message
     * @param string $email
     * @param string $name
     *
     * @return mixed
     */
    protected function mail($subject, $message, $email, $name = '')
    {

        $replacements = $this->quotation_replacements;

        // send the email
        return Mailer::sendMail(
            function ($mail) use ($subject, $message, $email, $name, $replacements) {

                // replace some quotation marks
                $subject = str_replace(array_keys($replacements), array_values($replacements), $subject);
                $message = str_replace(array_keys($replacements), array_values($replacements), $message);


                /** @var Mailer $mail */
                /** @var Template $template */
                $mail->Subject = $subject;
                $mail->Body = $message;

                $text = new Html2Text($message);
                $mail->AltBody = trim($text->getText());
                $mail->addAddress($email, $name);
            }
        );
    }

    /**
     * Replaces the replacements in message and subject
     *
     * @param Template $template
     * @param array         $replacements
     * @param bool          $replace_in_subject
     *
     * @return array
     */
    protected function replaceInTemplate(Template $template, array $replacements, $replace_in_subject = true)
    {

        // Set the keys
        $keys = array();
        foreach (array_keys($replacements) as $key) {
            $keys[] = '{' . $key . '}';
        }

        $message = str_replace($keys, array_values($replacements), $template->message);

        if ($replace_in_subject) {
            $subject = str_replace(
                $keys,
                array_values($replacements),
                $template->subject
            );
        } else {
            $subject = $template->subject;
        }
        return array($message, $subject,);
    }
}
