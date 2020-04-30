<?php
/**
 * File: PublicationToc.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Interfaces;

use Project\Models\AbstractPublication;

interface PublicationToc
{
    /**
     * Updates the issue's toc from user form
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return bool
     */
    public static function updateTocForPublicationFromForm(AbstractPublication $publication, array $data);

    /**
     * Deletes the issue toc by ids
     *
     * @param $id_list
     */
    public static function deleteById(array $id_list);

    /**
     * Returns the temp id
     *
     * @return bool|string
     */
    public function getTempId();
}
