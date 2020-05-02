<?php
/**
 * File: SubmissionLikes.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class SubmissionLike
 *
 * @property int user_id
 * @package Project\Models
 */
class SubmissionLike extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'submission' => '\Project\Models\Submission',
    );

    /**
     * @var string
     */
    protected static $table = 'submission_likes';
}
