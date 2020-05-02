<?php
/**
 * File: SubmissionStatus.php
 * Created: 26-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class SubmissionStatus
 *
 * @package Project\Models
 */
class SubmissionStatus extends ORM
{
    /**
     * Name of the all status
     */
    const ALL = 'all';

    /**
     * Slug of the accepted status
     */
    const STATUS_ACCEPTED = 'accepted';

    /**
     * Slug of the declined status
     */
    const STATUS_DECLINED = 'declined';

    /**
     * Slug of the new status
     */
    const STATUS_NEW = 'new';

    /**
     * Slug of the in-progress status
     */
    const STATUS_PROGRESS = 'in-progress';

    /**
     * Slug of the signed status
     */
    const STATUS_SIGNED = 'signed';

    /**
     * Slug of the archived status
     */
    const STATUS_ARCHIVED = 'archived';

    /**
     * Slug of the withdrawn status
     */
    const STATUS_WITHDRAWN = 'withdrawn';

    /**
     * Slug of the partial withdrawn status
     */
    const STATUS_PARTIAL_WITHDRAWN = 'partial_withdrawn';

    /**
     * @var string
     */
    protected static $foreign_key = 'submission_status_id';

    /**
     * @var string
     */
    protected static $table = 'submission_statuses';
}
