<?php
/**
 * File: NewsletterContent.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Html2Text\Html2Text;
use Story\Collection;
use Story\ORM;
use StoryEngine\StoryEngine;

/**
 * Class NewsletterContent
 *
 * @package Project\Models
 */
class NewsletterContent extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'newsletter' => '\Project\Models\Newsletter',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'newsletter_content';

    /**
     * Creates a newsletter content for a newsletter
     *
     * @param Newsletter $newsletter
     * @param            $subject
     * @param            $content
     *
     * @return static
     */
    public static function createContent(Newsletter $newsletter, $subject, $content)
    {
        $newsletter_content = new static;

        $content_text = new Html2Text($content);
        $newsletter_content->set(
            array(
                'newsletter_id' => $newsletter->id,
                'subject'       => $subject,
                'content'       => $content,
                'content_text'  => $content_text->getText()
            )
        );
        $newsletter_content->save();

        return $newsletter_content;
    }

    public static function updateContent(Newsletter $newsletter, $subject, $content)
    {
        /** @var Collection $content */
        $newsletter_content = $newsletter->content;
        $newsletter_content->load();
        // change if u need localization
        $newsletter_content = $newsletter_content->first();

        /** @var NewsletterContent $newsletter_content */
        $content_text = new Html2Text($content);
        $newsletter_content->set(
            array(
                'newsletter_id' => $newsletter->id,
                'subject'       => $subject,
                'content'       => $content,
                'content_text'  => $content_text->getText()
            )
        );
        return $newsletter_content->save();
    }

    /**
     * Content attribute accessor. Also executes the php echo statements like {{ time() }}
     * @param $value
     * @return mixed
     */
    public function getContentAttribute($value)
    {
        /** @var StoryEngine $engine */
        $engine = app('storyengine');
        $parser = $engine->getParser();
        $parser->execute($value);

        return $value;

    }
}
