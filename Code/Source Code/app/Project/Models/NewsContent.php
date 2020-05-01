<?php
/**
 * File: NewsContent.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;
use StoryEngine\StoryEngine;

/**
 * Class NewsContent
 *
 * @package Project\Models
 */
class NewsContent extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'issue' => '\Project\Models\News',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'news_content';

    /**
     * Creates the content for the article
     *
     * @param News        $article
     * @param             $title
     * @param array       $input
     * @param null|string $locale
     */
    public static function createArticleContent(News $article, $title, array $input, $locale = null)
    {

        if (!$locale) {
            $locale = app('locale');
        }

        // insert article content
        foreach (get_news_sections() as $section_type => $sections) {

            foreach ($sections as $name) {

                $input_name = $section_type . '-section-' . $name;

                $content = new static;
                $content->set(
                    array(
                        'news_id'      => $article->id,
                        'name'         => $name,
                        'locale'       => $locale,
                        'title'        => $title,
                        'content'      => $input[$input_name],
                        'content_text' => $input[$input_name . '_text']
                    )
                );

                $content->save();

            }
        }
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
