<?php
/**
 * File: PublicationFile.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Interfaces;

use Project\Models\AbstractPublication;

/**
 * Interface PublicationFile
 * @package Project\Interfaces
 */
interface PublicationFile
{
    /**
     * Creates the file for the issue from user input (form)
     *
     * @param AbstractPublication  $issue
     * @param array  $data
     * @param string $key
     *
     * @return mixed
     */
    public static function createPublicationCoverImageFromForm(AbstractPublication $issue, array $data, $key = 'file');

    /**
     * Return the table name
     *
     * @return mixed
     */
    public static function getTable();

    /**
     * Returns a single row
     *
     * @param null $where
     *
     * @return mixed
     */
    public static function one($where = null);

    /**
     * Returns the storage path
     *
     * @return string
     */
    public static function getCoverStoragePath();

    /**
     * Updates the cover image for the issue
     *
     * @param AbstractPublication $publication
     * @param       $data
     *
     * @return mixed
     */
    public static function updatePublicationCoverImageFromForm(AbstractPublication $publication, $data);

    /**
     * Returns the storage name
     *
     * @return string
     */
    public function getStorageName();

    /**
     * Delete the current object (and all related objects) from the database
     *
     * @param int $id to delete
     *
     * @return int
     */
    public function delete($id = null);
}
