<?php
/**
 * File: NewsletterSubscribeConfirmationEvent.php
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

class NewsletterSubscribeConfirmationEvent extends AbstractEventHandler
{
    /**
     * @var Template
     */
    public $template;

    /**
     * @var string
     */
    public $email;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {
        $this->email = func_get_arg(0);

        // get the template and replace the needed parts
        $this->template = Template::one(array('type' => 'newsletter', 'name' => 'confirm_subscribe'));

    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        $replacements = array(
            'subscribe'                 => HTML::link(
                NewsletterSubscriber::encodeAsActionFromId(
                    $this->email,
                    '\Project\Controllers\Newsletter\Subscribe'
                ),
                _('Confirm newsletter subscription')
            )
        );

        // Replace the template message and subject
        list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);


        // Send email
        $this->mail($subject, $message, $this->email);
    }
}
