<?php
/**
 * File: SubmissionCoverletter.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class SubmissionCoverletter
 *
 * @package Project\Models
 */
class SubmissionCoverletter extends ORM
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
    protected static $table = 'submission_coverletters';
}
