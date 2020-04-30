<?php
/**
 * File: Issue.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

class Issue extends AbstractPublication
{
    /**
     * Publication type
     */
    const TYPE = 'issue';

    public static $publication_file_repository = '\Project\Models\IssueFile';

    public static $publication_content_repository = '\Project\Models\IssueContent';

    public static $publication_toc_repository = '\Project\Models\IssueToc';

    public static $publication_toc_content_repository = '\Project\Models\IssueTocContent';

    public static $publication_toc_title_repository = '\Project\Models\IssueTocTitle';
    /**
     * @var array
     */
    public static $has_many = array(
        'contents'     => 'Project\Models\IssueContent',
        'toc'          => 'Project\Models\IssueToc',
        'toc_contents' => 'Project\Models\IssueTocContent',
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'issue_id';

    /**
     * @var string
     */
    protected static $table = 'issues';

    /**
     * Creates the issue from user input (form)
     *
     * @param $data
     *
     * @return bool|static
     */
    public static function createIssueFromForm($data)
    {
        return static::createFromForm($data);
    }

    /**
     * Returns the current issue highlights
     *
     * @param int $total Total number of highlights
     *
     * @return bool|\Project\Support\Publications\TocContentCollection
     */
    public static function getCurrentIssueHighlights($total = 3)
    {
        return static::getCurrentHighlights($total);
    }

    /**
     * List the issues matching the query and sort by date desc
     *
     * @param string $query
     * @param int    $current
     * @param int    $per_page
     *
     * @return array
     */
    public static function listIssuesByQuery($query, $current, $per_page)
    {
        return static::listByQuery($query, $current, $per_page);
    }

    /**
     * List the issues sorted by date descending
     *
     * @param int   $current
     * @param int   $per_page
     *
     * @param array $where
     *
     * @return array
     */
    public static function listIssues($current, $per_page, array $where = null)
    {
        return static::listPublications($current, $per_page, $where);
    }

    /**
     * Updates the issue from user data
     *
     * @param array $data
     *
     * @return $this|bool
     */
    public function updateIssueFromForm(array $data)
    {
        return static::updateFromForm($data);
    }

    /**
     * Returns the order type like: Issue, Chapbook, etc.
     * @return string
     */
    public function getOrderType()
    {
        return 'Issue';
    }

    /**
     * @return string
     */
    public function getAdminLink()
    {
        if (has_access('admin_issues_show')) {
            return action('\Project\Controllers\Admin\Issues\Show', array($this->key()));
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        if (has_access('issues_index')) {
            return action('\Project\Controllers\Issues\Index', array($this->slug));
        }

        return false;
    }
}
