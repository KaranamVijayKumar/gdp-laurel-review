<?php
/**
 * File: AbstractPublicationTocTitle.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Html2Text\Html2Text;
use Story\ORM;

abstract class AbstractPublicationTocTitle extends ORM
{
    /**
     * Toc title link
     *
     * @var bool|string
     */
    public $link = false;

    /**
     * @var null|AbstractPublicationTocTitle
     */
    public $linked_content = null;
    /**
     * Temporary id for the new models
     *
     * @var bool|string
     */
    protected $temp_id = false;

    /**
     * Creates models from data for an AbstractPublicationToc model
     *
     * @param array                  $data
     * @param AbstractPublicationToc $model
     * @param                        $id
     * @param array                  $existing_title_id_list
     */
    public static function createModelsFromData(
        array $data,
        AbstractPublicationToc $model,
        $id,
        $existing_title_id_list = array()
    ) {

        // create the titles relations
        if (!$data['toc-is_header'][$id] && isset($data['toc_titles-content'][$id])
            && count($data['toc_titles-content'][$id])
        ) {

            foreach ($data['toc_titles-content'][$id] as $title_id => $title_content) {

                $title = new static;
                $text = new Html2Text(trim($title_content));
                $text = trim($text->getText());

                if (!$text) {
                    continue;
                }

                $title->set(
                    array(
                        'order'        => $data['toc_titles-order'][$id][$title_id],
                        'content'      => $title_content,
                        'content_text' => $text
                    )
                );

                // if the title id is a number and exists in the existing id list, we consider this a valid model
                if (ctype_digit((string)$title_id) && in_array($title_id, $existing_title_id_list)) {
                    $title->set(array('id' => $title_id));
                } else {
                    // otherwise a new model
                    $title->temp_id = $title_id;
                }

                $model->related['titles'][] = $title;
            }
        }
    }

    /**
     * Deletes the publication toc by ids
     *
     * @param $id_list
     */
    public static function deleteById(array $id_list)
    {


        if (count($id_list)) {

            $db = static::$db;
            $tbl = static::getTable();
            $sql = "DELETE FROM {$tbl} WHERE {$db->i('id')} IN ("
                . implode(',', array_fill(0, count($id_list), '?')) . ")";

            static::$db->delete($sql, $id_list);
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
