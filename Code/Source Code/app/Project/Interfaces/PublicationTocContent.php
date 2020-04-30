<?php
/**
 * File: PublicationTocContent.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Interfaces;

use Story\Collection;

/**
 * Interface PublicationTocContent
 * @package Project\Interfaces
 */
interface PublicationTocContent
{
    /**
     * Returns random highlights for the issues
     *
     * @param Collection $issues
     * @param int        $content_limit
     *
     * @return array
     */
    public static function getRandomHighlighted(Collection $issues, $content_limit = 3);
}
