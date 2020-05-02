<?php
/**
 * File: SubmissionComment.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Story\ORM;

/**
 * Class SubmissionComment
 *
 * @package Project\Models
 */
class SubmissionComment extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'submission' => '\Project\Models\Submission',
    );

    /**
     * @var bool
     */
    public static $timestamps = true;

    /**
     * @var string
     */
    protected static $table = 'submission_comments';

    /**
     * Created accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getCreatedAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }

    /**
     * Created accessor
     *
     * @param $value
     *
     * @return Carbon
     */
    public function getModifiedAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }
}
