<?php
/**
 * File: NewsFactory.php
 * Created: 18-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\News;

use Project\Models\News;
use Project\Models\NewsContent;
use Story\Collection;
use Story\NotFoundException;
use Story\Pagination;

/**
 * Class NewsFactory
 *
 * @package Project\Support\News
 */
class NewsFactory
{
    /**
     * @var array
     */
    protected $project;


    /**
     * @var int
     */
    protected $latest_total = 5;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->project = app('project');
    }


    /**
     * @param $slug
     *
     * @return News
     * @throws \Story\NotFoundException
     */
    public function getArticleBySlug($slug)
    {
        /** @var News $article */
        $article = News::one(array('slug' => $slug, 'status' => '1'));

        if (!$article) {
            throw new NotFoundException('News article not found.');
        }
        // get the content based on the current locale
        $content = $article->getLocalizedContent();

        $sections = array();
        foreach ($content as $item) {
            $name = $item->name;
            $sections[$name] = $item;
        }

        $article->set(array('sections' => $sections));
        return $article;
    }

    /**
     *
     * @return int
     */
    public function getLatestTotal()
    {
        return $this->latest_total;
    }

    /**
     * Returns the latest news headlines
     *
     * @param News $skip_article
     *
     * @return Collection
     *
     */
    public function latest(News $skip_article = null)
    {
        $where = array('status' => '1');
        $i = News::$db->i;

        if ($skip_article) {
            $where[] = "{$i}id{$i} != " . (int) $skip_article->id;
        }
        // get the last $items from the news tbl, ordered by created desc
        $news = new Collection(News::all($where, $this->latest_total, 0, array('created'=>'desc')));

        // get the headline section for the items
        $collection = new Collection();
        if (count($news)) {
            $this->addNewsContent($news, $collection, 'headline');
        }

        return $collection;
    }

    /**
     * Returns the older news
     *
     * @return array
     */
    public function older()
    {
        // get the latest news ids
        $where = array('status' => '1');
        $latest = new Collection(News::all($where, $this->latest_total, 0, array('created'=>'desc')));

        if (!count($latest)) {
            return array('items' => false, 'pagination' => false, 'total' => false);
        }

        // get the news that are not latest and paginate
        $current = (int)get('page', 1);
        $offset = config('per_page') * ($current - 1);
        $per_page = config('per_page');
        $i = News::$db->i;

        $newsTbl = $i . 'news' . $i;
        $newsTbl_id = $newsTbl . '.' .$i . 'id' . $i;
        $newsContentTbl = $i . 'news_content' . $i;


        $select = "{$newsTbl_id},{$newsTbl}.{$i}slug{$i},{$newsTbl}.{$i}created{$i}, {$newsContentTbl}.{$i}title{$i},".
            "{$newsContentTbl}.{$i}content{$i}";
        $join = "INNER JOIN {$newsContentTbl} ON {$newsTbl_id} = {$newsContentTbl}.{$i}news_id{$i} ".
            "AND {$newsContentTbl}.{$i}name{$i} = \"headline\"\n";
        $where = "WHERE {$newsContentTbl}.{$i}locale{$i} = \"" . app('locale') ."\" ".
            "AND {$newsTbl}.{$i}status{$i} = \"1\" ".
            "AND {$newsTbl_id} NOT IN (".implode(',', $latest->lists('id')).")\n";
        $order = "ORDER BY {$newsTbl}.{$i}created{$i} DESC\n";
        $limit = "LIMIT {$per_page} OFFSET {$offset}";

        $sql = "SELECT {$select} \n FROM {$newsTbl} \n {$join} {$where} {$order} {$limit}";

        $items = News::$db->fetch($sql);
        if ($items) {
            foreach ($items as $k => $item) {
                $items[$k] = new News($item);
            }
        }

        $items = new Collection($items);

        // count
        $sql = "SELECT COUNT(*) as counter FROM {$newsTbl} \n {$join} {$where}";
        /** @var \stdClass $total */
        $total = News::$db->row($sql);
        $total = $total->counter;

        $pagination = '';

        if ($total > $per_page) {
            $pagination = new Pagination((int) $total, $current, $per_page);

        }

        return compact('items', 'pagination', 'total');
    }

    /**
     * Adds news content to the news models
     *
     * @param Collection $news
     * @param Collection $collection
     * @param string     $name
     */
    protected function addNewsContent(Collection $news, Collection $collection, $name = 'headline')
    {
        if (count($news)) {
            $i = NewsContent::$db->i;
            $ids = $news->lists();

            $idPlaceholders = trim(str_repeat('?,', count($ids)), ',');
            $sql = ("SELECT {$i}id{$i}, {$i}news_id{$i}, {$i}content{$i},{$i}title{$i} " .
                "FROM {$i}news_content{$i} WHERE {$i}news_id{$i} IN ({$idPlaceholders}) AND {$i}name{$i} = ?");

            $data = array_merge($ids, array($name));

            $items = NewsContent::$db->fetch($sql, $data);

            if ($items) {
                foreach ($items as $k => $item) {
                    $items[$k] = new NewsContent($item);
                }
            }
            $headlines = new Collection($items);

            // populate the collection with the news and headlines
            foreach ($headlines as $headline) {
                $item = $news->findBy('id', $headline->news_id);
                $item->$name = $headline;
                $collection->unshift($item);
            }
        }
    }

    /**
     * @param int $latest_total
     */
    public function setLatestTotal($latest_total)
    {
        $this->latest_total = $latest_total;
    }
}
