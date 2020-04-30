<?php
/**
 * File: ChapbookFile.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

class ChapbookFile extends AbstractPublicationFile
{
    /**
     * Relative storage path
     *
     */
    const RELATIVE_STORAGE_PATH = 'uploads/chapbooks';

    /**
     * @var string
     */
    protected static $table = 'chapbook_files';

    /**
     * @var bool
     */
    public static $create_blurred = false;
}
