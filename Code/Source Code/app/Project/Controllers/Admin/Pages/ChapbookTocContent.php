<?php
/**
 * File: ChapbookTocContent.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Support\Publications\TocContentCollection;
use Story\ORM;

class ChapbookTocContent extends AbstractPublicationTocContent
{
    /**
     * @var string
     */
    public static $type = Chapbook::TYPE;

    /**
     * @var array
     */
    public static $belongs_to = array(
        'chapbook'  => '\Project\Models\Chapbook',
        'toc_title' => '\Project\Models\ChapbookTocTitle'
    );

    /**
     * @var string
     */
    public static $action = '\Project\Controllers\Chapbooks\TocContent';

    /**
     * @var string
     */
    protected static $table = 'chapbook_toc_contents';

    /**
     * @param $items
     *
     * @return TocContentCollection
     */
    public static function collection($items)
    {
        TocContentCollection::$type = static::$type;
        TocContentCollection::$publication_toc_title_repository = Chapbook::$publication_toc_title_repository;
        TocContentCollection::$publication_toc_repository = Chapbook::$publication_toc_repository;

        return new TocContentCollection($items);
    }
}
