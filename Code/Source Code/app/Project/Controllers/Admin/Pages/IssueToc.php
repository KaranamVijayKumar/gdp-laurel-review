<?php
/**
 * File: IssueToc.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Support\Publications\TocCollection;
use Story\ORM;

/**
 * Class IssueToc
 *
 * @property int|string $id
 * @package Project\Models
 */
class IssueToc extends AbstractPublicationToc
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'issue' => '\Project\Models\Issue',
    );

    /**
     * @var array
     */
    public static $has_many = array(
        'titles' => 'Project\Models\IssueTocTitle',
        'toc'    => 'Project\Models\IssueTocContent',
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'issue_toc_id';

    /**
     * @var string
     */
    protected static $table = 'issue_toc';

    /**
     * @param $items
     *
     * @return TocCollection
     */
    public static function collection($items)
    {
        TocCollection::$publication_toc_title_repository = Issue::$publication_toc_title_repository;
        TocCollection::$type = Issue::TYPE;
        return new TocCollection($items);
    }
    /**
     * Updates the issue's toc from user form
     *
     * @param AbstractPublication $issue
     * @param array $data
     *
     * @return bool
     */
    public static function updateTocForIssueFromForm(AbstractPublication $issue, array $data)
    {

        return static::updateTocForPublicationFromForm($issue, $data);
    }
}
