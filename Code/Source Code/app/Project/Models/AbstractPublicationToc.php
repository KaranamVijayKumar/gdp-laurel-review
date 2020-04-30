<?php
/**
 * File: AbstractPublicationToc.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Html2Text\Html2Text;
use Project\Interfaces\PublicationToc;
use Project\Support\Publications\TocCollection;
use Story\Collection;
use Story\Error;
use Story\ORM;

abstract class AbstractPublicationToc extends ORM implements PublicationToc
{
    /**
     * Temporary id for the new models
     *
     * @var bool|string
     */
    protected $temp_id = false;

    /**
     * @param $items
     *
     * @return TocCollection
     */
    public static function collection($items)
    {

        return new TocCollection($items);
    }

    /**
     * Updates the publication's toc from user form
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return bool
     */
    public static function updateTocForPublicationFromForm(AbstractPublication $publication, array $data)
    {

        try {
            static::$db->pdo->beginTransaction();


            $publication->removeCached();

            // get existing model id's for the publication (so we can delete the missing ones later)

            list($existing_toc_id_list, $existing_title_id_list) = self::getExistingTocAndTitleIds($publication);

            // create from the data the publication models
            list($returned_toc_id_list, $models) = static::createModelsFromData(
                $publication,
                $data,
                $existing_toc_id_list,
                $existing_title_id_list
            );

            // save the toc items and their titles
            // for performance we are using prepare statements
            static::saveAll($models);

            // get the saved model's titles

            /** @var AbstractPublicationToc $model */
            $titleModels = array();
            foreach ($models as $model) {
                if (isset($model->related['titles']) && count($model->related['titles'])) {
                    foreach ($model->related['titles'] as $title) {
                        $title->{$publication::TYPE . '_toc_id'} = $model->id;
                        $titleModels[] = $title;
                    }
                }
            }

            $toc_title_repo = $publication::$publication_toc_title_repository;
            $titleModels = new Collection($titleModels);
            // save all titles
            /** @var ORM $toc_title_repo */
            $toc_title_repo::saveAll($titleModels);

            $returned_title_id_list = $titleModels->lists('id');


            // delete all the toc items that were not returned in toc-content
            static::deleteById(array_values(array_diff($existing_toc_id_list, $returned_toc_id_list)));

            // delete all the toc title items that were not returned in the toc_titles-content
            /** @var PublicationToc $toc_title_repo */
            $toc_title_repo::deleteById(array_values(array_diff($existing_title_id_list, $returned_title_id_list)));

            static::$db->pdo->commit();
            return true;

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }
        return false;
    }

    /**
     * Returns the existing toc and title ids for a publication
     *
     * @param AbstractPublication $publication
     *
     * @return array
     */
    protected static function getExistingTocAndTitleIds(AbstractPublication $publication)
    {

        $toc_ids = static::lists('id', null, array($publication::TYPE . '_id' => $publication->id));

        $toc_title_repo = $publication::$publication_toc_title_repository;

        $title_ids = array();

        if (count($toc_ids)) {
            /** @var ORM $toc_title_repo */
            $title_ids = $toc_title_repo::lists(
                'id',
                null,
                array($publication::TYPE . '_toc_id IN (' . implode(',', $toc_ids) . ')')
            );
        }

        return array($toc_ids, $title_ids);
    }

    /**
     * Creates the models and ids from the form data
     *
     * @param AbstractPublication  $publication
     * @param array  $data
     * @param array  $existing_toc_id_list
     * @param array  $existing_title_id_list
     *
     * @param string $key
     *
     * @return array|TocCollection
     */
    protected static function createModelsFromData(
        AbstractPublication $publication,
        array $data,
        $existing_toc_id_list = array(),
        $existing_title_id_list = array(),
        $key = 'toc-content'
    ) {

        if (!isset($data[$key])) {
            return array(array(), new TocCollection);
        }

        $returned_ids = array();
        $items = array();
        foreach ($data[$key] as $id => $content) {

            $content = trim($content);

            $text = new Html2Text(trim($content));
            $text = trim($text->getText());

            if (!$text) {
                continue;
            }

            $model = new static;
            $model->set(
                array(
                    $publication::TYPE . '_id'     => $publication->id,
                    'order'        => $data['toc-order'][$id],
                    'is_header'    => $data['toc-is_header'][$id],
                    'content'      => $content,
                    'content_text' => $text,
                )
            );

            $toc_title_repo = $publication::$publication_toc_title_repository;

            // create the titles relations
            $toc_title_repo::createModelsFromData($data, $model, $id, $existing_title_id_list);

            $items[] = $model;

            // if the id is valid and exists for the publication, we consider this an existing model
            if (ctype_digit((string)$id) && in_array($id, $existing_toc_id_list)) {
                $model->set(array('id' => $id));
                $returned_ids[] = $id;
            } else {
                // otherwise a new model
                $model->temp_id = $id;
            }

        }

        return array($returned_ids, new TocCollection($items));
    }

    /**
     * Deletes the publication toc by ids
     *
     * @param $id_list
     */
    public static function deleteById(array $id_list)
    {

        $db = static::$db;
        if (count($id_list)) {
            $tbl = static::getTable();
            $sql = "DELETE FROM {$tbl} WHERE
                {$db->i('id')} IN (" . implode(',', array_fill(0, count($id_list), '?')) . ")";

            static::$db->delete($sql, array_values($id_list));
        }
    }

    /**
     * Returns the temp id
     *
     * @return bool|string
     */
    public function getTempId()
    {

        return $this->temp_id;
    }
}
