<?php
/**
 * File: NewsletterSentEvent.php
 * Created: 07-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Project\Models\Newsletter;

/**
 * Class NewsletterSentEvent
 * @package Project\Support\Events\Handlers
 */
class NewsletterSentEvent extends AbstractEventHandler
{

    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * Event handler constructor
     *
     */
    public function __construct()
    {
        $this->newsletter = func_get_arg(0);
    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        // Log
        $this->log(
            $this->newsletter,
            'Newsletter {newsletter} sent.',
            array(
                'newsletter'          => $this->newsletter->id,
                'newsletter_fallback' => $this->newsletter->content->first()->subject
            )
        );
    }
}
