<?php
/**
 * File: SubmissionPartial.php
 * Created: 25-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

class SubmissionPartial extends ORM
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
    protected static $table = 'submission_partials';
}
