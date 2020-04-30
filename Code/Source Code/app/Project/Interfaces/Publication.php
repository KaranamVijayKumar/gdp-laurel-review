<?php
/**
 * File: Publication.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Interfaces;

use Story\Collection;

/**
 * Interface Publication
 * @package Project\Interfaces
 */
interface Publication
{
    /**
     * Creates the publication from user input (form)
     *
     * @param $data
     *
     * @return bool|static
     */
    public static function createFromForm($data);

    /**
     * Returns the last publication
     *
     * @return \stdClass
     */
    public static function getLast();

    /**
     * List the publication matching the query and sort by date desc
     *
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     *
     * @return array
     */
    public static function listByQuery($query, $current, $per_page);

    /**
     * List the publications sorted by date descending
     *
     * @param int   $current
     * @param int   $per_page
     *
     * @param array $where
     *
     * @return array
     */
    public static function listPublications($current, $per_page, array $where = null);

    /**
     * Get latest publications with highlights
     *
     * @param int   $limit
     * @param int   $content_limit
     * @param array $where
     *
     * @return Collection
     */
    public static function withTocHighlights($limit = 3, $content_limit = 3, array $where = null);

    /**
     * Removes the model and deletes the related files
     *
     * @return bool|int
     */
    public function deleteWithFiles();

    /**
     * Removes the cached data for the publication
     *
     * @return bool
     */
    public function removeCached();

    /**
     * Caches the current page if needed
     *
     *
     * @return Publication
     */
    public function getCached();

    /**
     * Updates the publication from user data
     *
     * @param array $data
     *
     * @return $this|bool
     */
    public function updateFromForm(array $data);

    /**
     * Returns true if the current publication is the latest one
     *
     * @return bool
     */
    public function isLatest();
}
