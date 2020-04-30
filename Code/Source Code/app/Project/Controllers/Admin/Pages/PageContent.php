<?php
/**
 * File: PageContent.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\ORM;
use StoryEngine\StoryEngine;

/**
 * Class PageContent
 *
 * @package Project\Models
 */
class PageContent extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'page' => '\Project\Models\Page',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'page_contentv1';

    /**
     * Creates the content for the page
     *
     * @param Page $page
     * @param array $input
     * @param null|string $locale
     */
    public static function createContents(Page $page, array $input, $locale = null)
    {
        if (!$locale) {
            $locale = app('locale');
        }

        require_once SP . 'Project/Support/Pages/pages_helpers.php';

        // insert article content
        foreach (get_pages_sections() as $section_type => $sections) {

            foreach ($sections as $name) {

                // $input_name = $section_type . '-section-' . $name;

                $content = new static;
                $content->set(
                    array(
                        'page_id'      => $page->id,
                        'name'         => $name,
                        'locale'       => $locale,
                        'title'        => $input['title'],
                        'content'      => '', // we leave it empty since we do not need content yet
                        'content_text' => ''  // we leave it empty since we do not need content yet
                    )
                );

                $content->save();

            }
        }
    }

    /**
     * Updates the page title for the current locale
     *
     * @param Page $page
     * @param $value
     * @param null $locale
     * @return int
     */
    public static function updateTitleForPage(Page $page, $value, $locale = null)
    {
        if (!$locale) {
            $locale = app('locale');
        }

        // Get the page id
        $id = $page->id;

        // remove the cache
        event('page.saved', $page);

        return static::$db->update(
            static::getTable(),
            array('title' => $value),
            array('page_id' => $id, 'locale' => $locale)
        );
    }

    /**
     * Updates the content for the page
     *
     * @param Page $page
     * @param $data
     * @param null|string $locale
     */
    public static function updateContentForPage(Page $page, $data, $locale = null)
    {
        if (!$locale) {
            $locale = app('locale');
        }

        // Get the page id
        $id = $page->id;

        // get the sections
        $all_sections = get_pages_sections();
        $params = array($id, $locale);

        // update the optional sections
        static::updateContentBySections('optional', $all_sections['optional'], $params, $data);

        // and the required sections
        static::updateContentBySections('required', $all_sections['required'], $params, $data);

    }

    /**
     * Updates the content by section type
     *
     * @param string $section_type
     * @param array $section_names
     * @param array $params
     * @param array $data
     * @return array
     */
    protected static function updateContentBySections($section_type, array $section_names, array $params, array $data)
    {
        foreach ($section_names as $section_name) {
            $params[] = $section_name;
        }
        // get the rows
        $db = static::$db;
        $sql = "SELECT * FROM {$db->i(static::getTable())}"
            . ' WHERE ' . $db->i('page_id') . ' = ? AND ' . $db->i('locale') . ' = ? AND '
            . $db->i('name') . ' IN (' . rtrim(str_repeat('?, ', count(array_slice($params, 2))), ', ') . ')';
        $rows = static::$db->fetch($sql, $params);

        $result = array();
        if (count($rows)) {
            foreach ($rows as $k => $row) {
                $rows[$k] = new static($row);
            }
            $rows = new Collection($rows);
            // update the rows
            foreach ($section_names as $section_name) {

                $model = $rows->findBy('name', $section_name);

                $content = $data[$section_type . '-section-' . $section_name];
                $content_text = $data[$section_type . '-section-' . $section_name . '_text'];

                if ($model) {
                    $model->content = $content;
                    $model->content_text = $content_text;


                } else {
                    // We insert a new model
                    $model = new static;
                    $model->set(
                        array(
                            'page_id'      => $params[0],
                            'name'         => $section_name,
                            'locale'       => $params[1],
                            'title'        => '',
                            'content'      => $content,
                            'content_text' => $content_text,

                        )
                    );
                }
                $result[] = $model->save();
            }
        }

        return $result;
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
