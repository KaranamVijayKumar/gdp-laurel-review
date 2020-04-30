<?php
/**
 * File: IssueTocTitle.php
 * Created: 28-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * @property int|string id
 */
class IssueTocTitle extends AbstractPublicationTocTitle
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'toc' => '\Project\Models\IssueToc',
    );

    /**
     * @var string
     */
    protected static $table = 'issue_toc_titles';


    /**
     * @var string
     */
    protected static $foreign_key = 'issue_toc_title_id';
}
