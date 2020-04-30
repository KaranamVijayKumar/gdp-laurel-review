<?php
/**
 * File: NewsletterUnsubscribeConfirmationEvent.php
 * Created: 07-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Project\Models\NewsletterSubscriber;
use Project\Models\Template;
use Story\HTML;

class NewsletterUnsubscribeConfirmationEvent extends AbstractEventHandler
{

    /**
     * @var NewsletterSubscriber
     */
    public $subscriber;

    /**
     * @var Template
     */
    public $template;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {
        $this->subscriber = func_get_arg(0);

        // get the template and replace the needed parts
        $this->template = Template::one(array('type' => 'newsletter', 'name' => 'confirm_unsubscribe'));

    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        $replacements = array(
            'unsubscribe'                 => HTML::link(
                $this->subscriber->encodeAsAction('\Project\Controllers\Newsletter\Unsubscribe'),
                _('Cancel newsletter')
            )
        );

        // Replace the template message and subject
        list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);


        // Send email
        $this->mail($subject, $message, $this->subscriber->email);
    }
}
