<?php
/**
 * File: Chapbook.php
 * Created: 19-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

class Chapbook extends AbstractPublication
{
    /**
     * Publication type
     */
    const TYPE = 'chapbook';

    public static $publication_file_repository = '\Project\Models\ChapbookFile';

    public static $publication_content_repository = '\Project\Models\ChapbookContent';

    public static $publication_toc_repository = '\Project\Models\ChapbookToc';

    public static $publication_toc_content_repository = '\Project\Models\ChapbookTocContent';

    public static $publication_toc_title_repository = '\Project\Models\ChapbookTocTitle';

    /**
     * @var array
     */
    public static $has_many = array(
        'contents'     => 'Project\Models\ChapbookContent',
        'toc'          => 'Project\Models\ChapbookToc',
        'toc_contents' => 'Project\Models\ChapbookTocContent',
    );


    /**
     * @var string
     */
    protected static $foreign_key = 'chapbook_id';

    /**
     * @var string
     */
    protected static $table = 'chapbooks';

    /**
     * Returns the order type like: Issue, Chapbook, etc.
     * @return string
     */
    public function getOrderType()
    {
        return 'Chapbook';
    }

    /**
     * @return string
     */
    public function getAdminLink()
    {
        if (has_access('admin_chapbooks_show')) {
            return action('\Project\Controllers\Admin\Chapbooks\Show', array($this->key()));
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        if (has_access('chapbooks_index')) {
            return action('\Project\Controllers\Chapbooks\Index', array($this->slug));
        }

        return false;
    }
}
