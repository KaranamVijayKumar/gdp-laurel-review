<?php
/**
 * File: PublicationContent.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Interfaces;

use Project\Models\AbstractPublication;

/**
 * Interface PublicationContent
 * @package Project\Interfaces
 */
interface PublicationContent
{

    /**
     * Inserts the content for the issue from the form data
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return int|string
     */
    public static function createContentForPublicationFromForm(AbstractPublication $publication, array $data);

    /**
     * Return the table name
     *
     * @return mixed
     */
    public static function getTable();

    /**
     * @return array
     */
    public static function getRequiredSections();

    /**
     * Updates the contents for the issue
     *
     * @param AbstractPublication $publication
     * @param array $data
     *
     * @return string
     */
    public static function updateContentsForPublication(AbstractPublication $publication, array $data);
}
