<?php
/**
 * File: ChapbookTocTitle.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

class ChapbookTocTitle extends AbstractPublicationTocTitle
{
    /**
     * @var array
     */
    public static $belongs_to = array(
        'toc' => '\Project\Models\ChapbookToc',
    );

    /**
     * @var string
     */
    protected static $table = 'chapbook_toc_titles';

    /**
     * @var string
     */
    protected static $foreign_key = 'chapbook_toc_title_id';
}
