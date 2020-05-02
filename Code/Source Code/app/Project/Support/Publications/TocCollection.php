<?php
/**
 * File: TocCollection.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Publications;

use Story\Collection;
use Story\ORM;

/**
 * Class TocCollection
 * @package Project\Support\Publications
 */
class TocCollection extends Collection
{
    /**
     * @var ORM
     */
    public static $publication_toc_title_repository;

    /**
     * @var string
     */
    public static $type = 'publication';

    /**
     * Loads the toc items with their titles relations
     *
     * @return mixed|null
     */
    public function loadWithTitles()
    {
        $return = parent::load();

        $ids = array();
        foreach ($this->items as $item) {
            if (!$item->is_header) {
                $ids[] = $item->id;
            }
        }

        if (!count($ids)) {
            return false;
        }

        $toc_title_repo = static::$publication_toc_title_repository;

        $db = $toc_title_repo::$db;
        $titles = $toc_title_repo::$db->fetch(
            "SELECT * FROM {$db->i($toc_title_repo::getTable())} WHERE {$db->i(static::$type . '_toc_id')} IN (" .
            trim(str_repeat('?,', count($ids)), ',') . ") ORDER BY {$db->i('order')} ASC",
            $ids
        );


        // associate the titles with the models
        foreach ($titles as $title) {
            /** @var ORM $collectionItem */
            $collectionItem = $this->get($title->{static::$type . '_toc_id'});
            if (!$collectionItem) {
                continue;
            }

            /** @var ORM $model */
            $model = new $toc_title_repo;
            $model->set((array)$title);
            $model->saved = $model->loaded = true;
            $collectionItem->related['titles'][] = $model;
        }

        return $return;
    }
}
