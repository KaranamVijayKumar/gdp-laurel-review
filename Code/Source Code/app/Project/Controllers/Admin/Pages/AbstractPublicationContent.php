<?php
/**
 * File: AbstractPublicationContent.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Html2Text\Html2Text;
use Project\Interfaces\PublicationContent;
use Story\ORM;
use StoryEngine\StoryEngine;

/**
 * Class AbstractPublicationContent
 * @package Project\Models
 */
abstract class AbstractPublicationContent extends ORM implements PublicationContent
{
    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * Optional table of content sections
     *
     * @var array
     */
    public static $optional_toc_sections = array('before TOC', 'after TOC', 'aside TOC');

    /**
     * Required content sections. First value is used for the list preview
     *
     * @var array
     */
    public static $required_sections = array('short_description');

    /**
     * Inserts the content for the publication from the form data
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return int|string
     */
    public static function createContentForPublicationFromForm(AbstractPublication $publication, array $data)
    {

        // required sections
        $inserted_row_count = self::createRequiredSections($publication, $data);

        // optional sections
        $inserted_row_count .= self::createOptionalTocSections($publication, $data);

        return $inserted_row_count;
    }

    /**
     * Creates the required section content
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return int
     */
    protected static function createRequiredSections(AbstractPublication $publication, array $data)
    {

        $inserted_row_count = 0;

        foreach (static::$required_sections as $section) {

            if ($data[$section]) {
                $content = new static;
                $text = new Html2Text($data[$section]);
                $content->set(
                    array(
                        $publication::TYPE . '_id'     => $publication->id,
                        'name'         => $section,
                        'title'        => $publication->title,
                        'content'      => $data[$section],
                        'content_text' => $text->getText()
                    )
                );
                $content->save();
                $inserted_row_count++;
            }

        }

        return $inserted_row_count;
    }

    /**
     * Creates the optional toc section content when content exists
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return int
     */
    protected static function createOptionalTocSections(AbstractPublication $publication, array $data)
    {

        $inserted_row_count = 0;

        foreach (static::$optional_toc_sections as $section) {
            $slug_data = 'optional-section-' . slug($section);

            if (isset($data[$slug_data]) && $data[$slug_data]) {
                $content = new static;
                $text = new Html2Text($data[$slug_data]);
                $content->set(
                    array(
                        $publication::TYPE . '_id'     => $publication->id,
                        'name'         => $section,
                        'title'        => $publication->title,
                        'content'      => $data[$slug_data],
                        'content_text' => $text->getText()
                    )
                );
                $content->save();
                $inserted_row_count++;
            }
        }
        return $inserted_row_count;
    }

    /**
     * Updates the contents for the publication
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return string
     */
    public static function updateContentsForPublication(AbstractPublication $publication, array $data)
    {

        // required sections
        $updated_row_count = self::updateRequiredSections($publication, $data);

        // optional sections
        $updated_row_count .= self::updateOptionalTocSections($publication, $data);

        return $updated_row_count;
    }

    /**
     * Updates the required sections for the existing publication
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return int
     */
    protected static function updateRequiredSections(AbstractPublication $publication, array $data)
    {

        $inserted_row_count = 0;

        foreach (static::$required_sections as $section) {

            // find the content in the publication content collection
            /** @var static $content */
            $content = $publication->contents->findBy('name', $section, new static);

            // update the content
            $text = new Html2Text($data[$section]);
            $content->set(
                array(
                    $publication::TYPE . '_id'     => $publication->id,
                    'name'         => $section,
                    'title'        => $publication->title,
                    'content'      => $data[$section],
                    'content_text' => $text->getText()
                )
            );
            $content->save();
            $inserted_row_count++;
        }

        return $inserted_row_count;
    }

    /**
     * Updates the optional sections for the existing publication
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return int
     */
    protected static function updateOptionalTocSections(AbstractPublication $publication, array $data)
    {

        $inserted_row_count = 0;

        foreach (static::$optional_toc_sections as $section) {

            /** @var static $content */
            $content = $publication->contents->findBy('name', $section, new static);

            $slug_data = 'optional-section-' . slug($section);

            if (isset($data[$slug_data]) && $data[$slug_data]) {
                $text = new Html2Text($data[$slug_data]);
                $content->set(
                    array(
                        $publication::TYPE . '_id'     => $publication->id,
                        'name'         => $section,
                        'title'        => $publication->title,
                        'content'      => $data[$slug_data],
                        'content_text' => $text->getText()
                    )
                );
                $content->save();
                $inserted_row_count++;
            } else {
                $content->set(
                    array(
                        $publication::TYPE . '_id'     => $publication->id,
                        'name'         => $section,
                        'title'        => $publication->title,
                        'content'      => '',
                        'content_text' => ''
                    )
                );
                $content->save();
                $inserted_row_count++;
            }
        }
        return $inserted_row_count;
    }

    /**
     * @return array
     */
    public static function getRequiredSections()
    {
        return self::$required_sections;
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
