<?php
/**
 * File: ChapbookToc.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Support\Publications\TocCollection;
use Story\ORM;

class ChapbookToc extends AbstractPublicationToc
{
    /**
     * @var array
     */
    public static $belongs_to = array(
        'chapbook' => '\Project\Models\Chapbook',
    );

    /**
     * @var array
     */
    public static $has_many = array(
        'titles' => 'Project\Models\ChapbookTocTitle',
        'toc'    => 'Project\Models\ChapbookTocContent',
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'chapbook_toc_id';

    /**
     * @var string
     */
    protected static $table = 'chapbook_toc';

    /**
     * @param $items
     *
     * @return TocCollection
     */
    public static function collection($items)
    {
        TocCollection::$publication_toc_title_repository = Chapbook::$publication_toc_title_repository;
        TocCollection::$type = Chapbook::TYPE;
        return new TocCollection($items);
    }
}
