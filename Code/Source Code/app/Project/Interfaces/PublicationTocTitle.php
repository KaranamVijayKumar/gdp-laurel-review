<?php
/**
 * File: PublicationTocTitle.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Interfaces;

/**
 * Interface PublicationTocTitle
 * @package Project\Interfaces
 */
interface PublicationTocTitle
{
    /**
     * Creates models from data for an IssueToc model
     *
     * @param array $data
     * @param PublicationToc $model
     * @param                $id
     * @param array $existing_title_id_list
     */
    public static function createModelsFromData(
        array $data,
        PublicationToc $model,
        $id,
        $existing_title_id_list = array()
    );

    /**
     * Deletes the issue toc by ids
     *
     * @param array $id_list
     */
    public static function deleteById(array $id_list);

    /**
     * Returns the temp id
     *
     * @return bool|string
     */
    public function getTempId();
}
