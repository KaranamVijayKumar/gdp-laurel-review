<?php
/**
 * File: IssueTocContent.php
 * Created: 20-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Support\Publications\TocContentCollection;

/**
 * Class IssueTocContent
 *
 * @package Project\Models
 */
class IssueTocContent extends AbstractPublicationTocContent
{

    /**
     * @var string
     */
    public static $type = Issue::TYPE;

    /**
     * @var string
     */
    public static $action = '\Project\Controllers\Issues\TocContent';

    /**
     * @var array
     */
    public static $belongs_to = array(
        'issue'     => '\Project\Models\Issue',
        'toc_title' => '\Project\Models\IssueTocTitle'
    );

    /**
     * @var string
     */
    protected static $table = 'issue_toc_contents';

    /**
     * @param $items
     *
     * @return TocContentCollection
     */
    public static function collection($items)
    {
        TocContentCollection::$type = static::$type;
        TocContentCollection::$publication_toc_title_repository = Issue::$publication_toc_title_repository;
        TocContentCollection::$publication_toc_repository = Issue::$publication_toc_repository;
        return new TocContentCollection($items);
    }
}
