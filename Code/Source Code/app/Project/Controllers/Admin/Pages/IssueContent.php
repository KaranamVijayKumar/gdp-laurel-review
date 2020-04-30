<?php
/**
 * File: IssueContent.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class IssueContent
 *
 * @package Project\Models
 */
class IssueContent extends AbstractPublicationContent
{
    /**
     * @var array
     */
    public static $belongs_to = array(
        'issue' => '\Project\Models\Issue',
    );

    /**
     * @var string
     */
    protected static $table = 'issue_content';

    /**
     * Updates the contents for the issue
     *
     * @param AbstractPublication $issue
     * @param array $data
     *
     * @return string
     */
    public static function updateContentsForIssue(AbstractPublication $issue, array $data)
    {

        return static::updateContentsForPublication($issue, $data);
    }
}
