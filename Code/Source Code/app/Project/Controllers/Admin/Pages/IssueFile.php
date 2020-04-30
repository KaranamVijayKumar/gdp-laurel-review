<?php
/**
 * File: IssueFile.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

/**
 * Class IssueFile
 *
 * @property  $name
 * @property string $storage_name
 * @property string $mime
 * @package Project\Models
 */
class IssueFile extends AbstractPublicationFile
{

    /**
     * Relative storage path
     *
     */
    const RELATIVE_STORAGE_PATH = 'uploads/issues';

    /**
     * @var string
     */
    protected static $table = 'issue_files';

    /**
     * Updates the cover image for the issue
     *
     * @param AbstractPublication $issue
     * @param       $data
     *
     * @return mixed
     */
    public static function updateIssueCoverImageFromForm(AbstractPublication $issue, $data)
    {
        return static::updatePublicationCoverImageFromForm($issue, $data);
    }
}
