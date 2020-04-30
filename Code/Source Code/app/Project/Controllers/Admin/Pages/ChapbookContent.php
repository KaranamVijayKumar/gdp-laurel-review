<?php
/**
 * File: ChapbookContent.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

class ChapbookContent extends AbstractPublicationContent
{
    /**
     * @var array
     */
    public static $belongs_to = array(
        'chapbook' => '\Project\Models\Chapbook',
    );

    /**
     * @var string
     */
    protected static $table = 'chapbook_content';
}
