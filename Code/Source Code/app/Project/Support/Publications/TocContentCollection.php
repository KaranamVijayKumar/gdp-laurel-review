<?php
/**
 * File: TocContentCollection.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Publications;

use Story\Collection;
use Story\ORM;

/**
 * Class TocContentCollection
 * @package Project\Support\Publications
 */
class TocContentCollection extends Collection
{
    /**
     * @var ORM
     */
    public static $publication_toc_title_repository;

    /**
     * @var ORM
     */
    public static $publication_toc_repository;

    /**
     * @var string
     */
    public static $type = 'publication';

    /**
     * Adds a title_and_author relationship that contains the title and author
     *
     * @return mixed|null
     */
    public function loadWithTitlesAndAuthors()
    {
        $return = parent::load();

        // get the title ids
        $title_ids = array();
        foreach ($this->items as $item) {
            $title_ids[] = $item->issue_toc_title_id;
        }

        if (!count($title_ids)) {
            return false;
        }

        /** @var ORM $publicationToc */
        $publicationToc = static::$publication_toc_repository;
        /** @var ORM $publicationTocTitle */
        $publicationTocTitle = static::$publication_toc_title_repository;

        $i = $publicationToc::$db->i;
        $title_tbl = $publicationToc::$db->i($publicationTocTitle::getTable());
        $toc_tbl = $publicationToc::$db->i($publicationToc::getTable());
        $col_id = $publicationToc::$db->i('id');
        $col_content = $publicationToc::$db->i('content');

        $sql = "SELECT {$title_tbl}.{$col_id}, {$title_tbl}.{$col_content} as title, ".
            "{$toc_tbl}.{$col_content} as author \n".
            "FROM {$toc_tbl} \n".
            "INNER JOIN {$title_tbl} ON {$toc_tbl}.{$col_id} = {$title_tbl}.{$i}". static::$type ."_toc_id{$i}\n".
            "WHERE {$title_tbl}.{$col_id} IN (" . trim(str_repeat('?,', count($title_ids)), ',') . ")";

        $authorAndTitlesArray = $publicationToc::$db->fetch($sql, $title_ids);

        if (!count($authorAndTitlesArray)) {
            return false;
        }

        foreach ($authorAndTitlesArray as $data) {
            $item = $this->findBy(static::$type . '_toc_title_id', $data->id);
            if (!$item) {
                continue;
            }
            $item->related['title_and_author'] = $data;
            $item->saved = $item->loaded = true;
        }

        return $return;
    }
}
