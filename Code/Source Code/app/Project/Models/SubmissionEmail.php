<?php
/**
 * File: SubmissionEmail.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class SubmissionEmail
 *
 * @package Project\Models
 */
class SubmissionEmail extends ORM
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
    protected static $table = 'submission_emails';
}
