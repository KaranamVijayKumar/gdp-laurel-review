<?php
/**
 * File: Create.php
 * Created: 13-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Files;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\PublicAsset;

/**
 * Class Create
 * @package Project\Controllers\Admin\Files
 */
class Create extends AdminBaseController
{

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/files/create';

    /**
     * Selected menu item
     *
     * @var array
     */
    public $selected = array('pages', 'files');

    /**
     * Shows the file upload view
     */
    public function get()
    {
        $this->title = _('Upload files');

        /** @var \Story\Session $session */
        $session = $this->app['session'];
        $session->remove('fileupload_count');
    }

    /**
     * Stores the uploaded file
     */
    public function post()
    {
        $this->json = PublicAsset::storeUploadedFile();
    }
}
