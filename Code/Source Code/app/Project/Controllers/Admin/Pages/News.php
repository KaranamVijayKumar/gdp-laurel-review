<?php
/**
 * File: News.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\NotFoundException;
use Story\ORM;

/**
 * Class News
 *
 * @package Project\Models
 */
class News extends ORM
{

    /**
     * @var array
     */
    public static $has_many = array(
        'content' => 'Project\Models\NewsContent',
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'news_id';

    /**
     * @var string
     */
    protected static $table = 'news';

    /**
     * Creates the news article
     *
     * @param string $title
     * @param array  $input
     *
     * @return News
     */
    public static function createArticle($title, array $input)
    {

        $article = new static;
        $article->set(
            array(
                'slug'    => slug($title),
                'status'  => $input['status'],
                'created' => time()
            )
        );
        $article->save();
        return $article;
    }

    /**
     * List the articles matching the query and sort by date desc
     *
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     *
     * @return array
     */
    public static function listArticlesByQuery($query, $current, $per_page)
    {

        // 1. get the items
        $where = query_to_where($query, array('slug', 'title', 'content_text'), static::$db->i);

        return static::listArticles($current, $per_page, $where);
    }

    /**
     * List the articles sorted by date descending
     *
     * @param int   $current
     * @param int   $per_page
     *
     * @param array $where
     *
     * @return array
     */
    public static function listArticles($current, $per_page, array $where = null)
    {

        $offset = $per_page * ($current - 1);

        $i = static::$db->i;
        $news = static::$db->i(static::$table);
        $news_content = static::$db->i(NewsContent::getTable());
        $created = static::$db->i('created');


        $params = array('content', app('locale'));

        if ($where) {
            $params = array_merge($params, $where['values']);
        }

        // 1. get the items
        $sql = "SELECT {$news}.*, {$news_content}.{$i}title{$i}, {$news_content}.{$i}content_text{$i}\n" .
            "FROM {$news}\n" .
            "LEFT JOIN {$news_content} ON {$news}.{$i}id{$i} = {$news_content}.{$i}news_id{$i} " .
            "AND {$news_content}.{$i}name{$i} = ? AND {$news_content}.{$i}locale{$i} = ? \n" .
            ($where ? 'WHERE ' . $where['sql'] : '') .
            "ORDER BY {$created} DESC LIMIT {$offset}, {$per_page}";

        $items = new Collection(static::$db->fetch($sql, $params));

        // 2. count the total items
        $countSql = "SELECT COUNT(*) FROM {$news}" .
            "LEFT JOIN {$news_content} ON {$news}.{$i}id{$i} = {$news_content}.{$i}news_id{$i} " .
            "AND {$news_content}.{$i}name{$i} = ? AND {$news_content}.{$i}locale{$i} = ? \n" .
            ($where ? ' WHERE ' . $where['sql'] : '');

        $total = static::$db->column($countSql, $params);

        // 3. return the items
        return array('total' => $total, 'items' => $items);
    }

    /**
     * Deletes the article with it's content
     *
     * @return int
     */
    public function deleteWithContent()
    {

        // delete the content
        $db = static::$db;
        $tbl = static::$db->i(NewsContent::getTable());
        $sql = "DELETE FROM {$tbl} WHERE {$db->i('news_id')} = ?";
        static::$db->delete($sql, array($this->id));

        // delete the article
        return $this->delete();
    }

    /**
     * @param        $input
     * @param string $locale
     *
     * @return $this
     * @throws NotFoundException
     */
    public function updateArticle($input, $locale = 'en')
    {

        // we update the slug, status and modified
        $this->set(
            array(
//                'slug' => slug($input['title']),
                'status'   => $input['status'],
                'modified' => time(),
            )
        );

        $this->save();

        // get the existing sections
        $existing_sections = array();
        foreach ($this->getLocalizedContent($locale) as $item) {
            $name = $item->name;
            $existing_sections[$name] = $item;
        }

        list($required_sections, $optional_sections) = array_values(get_news_sections());

        $this->updateOrCreateSections($required_sections, $existing_sections, $input, $locale, 'required');
        $this->updateOrCreateSections($optional_sections, $existing_sections, $input, $locale, 'optional');


        return $this;
    }

    /**
     * Attempts to return the localized content for the news article
     *
     * @param string $default_locale
     *
     * @throws NotFoundException
     * @return Collection
     */
    public function getLocalizedContent($default_locale = 'en')
    {

        /** @var Collection $content */
        $this->content->load();
        $content = $this->content->findAllBy('locale', app('locale'));


        if (!count($content)) {
            // we spin through the existing languages and get a matching one
            foreach (config('languages') as $locale) {
                $content = $this->content->findAllBy('locale', $locale);
                if ($content) {
                    break;
                }
            }
        }

        // still no match? we show the default localized content
        if (!count($content)) {
            $content = $this->content->findAllBy('locale', $default_locale);
        }

        // we give up, and throw an not found
        if (!count($content)) {
            throw new NotFoundException('Page not found. [locale=' . $default_locale . ']');
        }
        return $content;
    }

    /**
     * Updates or creates the news content sections
     *
     * @param array  $sections
     * @param array  $existing_sections
     * @param array  $input
     * @param string $locale
     * @param string $type
     */
    private function updateOrCreateSections(array $sections, array $existing_sections, array $input, $locale, $type)
    {

        foreach ($sections as $section) {
            // if the section exists we update it, otherwise we create it
            if (array_key_exists($section, $existing_sections)) {
                $content = $existing_sections[$section];
            } else {
                $content = new NewsContent;
                $content->set(
                    array(
                        'news_id' => $this->id,
                        'name'    => $section,
                        'locale'  => $locale,
                    )
                );
            }

            if (isset($input[$type . '-section-' . $section])) {
                $content->set(
                    array(
                        'title'        => $input['title'],
                        'content'      => $input[$type . '-section-' . $section],
                        'content_text' => $input[$type . '-section-' . $section . '_text'],
                    )
                );

                $content->save();
            }
        }
    }
}
