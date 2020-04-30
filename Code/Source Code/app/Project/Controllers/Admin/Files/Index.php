<?php
/**
 * File: Index.php
 * Created: 25-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Files;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\PublicAsset;
use Story\Pagination;
use Story\View;

/**
 * Class Index
 * @package Project\Controllers\Admin\Files
 */
class Index extends AdminBaseController
{

    /**
     * @var string
     */
    public $template = 'admin/files/index';

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected;

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $query;

    /**
     * @var \Story\Pagination
     */
    public $pagination;

    /**
     * @var  \Story\Collection
     */
    public $items;

    /**
     * @var int
     */
    public $total;

    /**
     * Shows the file list
     */
    public function get()
    {

        $this->title = _('Files');
        $this->selected = array('pages', 'files');

        // We list the public assets sorted by name asc
        $this->query = substr(html2text(trim(get('q', null))), 0, 200);
        $current = (int)get('page', 1);

        // no query, we list assets by name
        if (!$this->query) {
            $items = PublicAsset::listFiles(
                $current,
                config('per_page')
            );
        } else {
            // we have query, get the assets filtered
            $items = PublicAsset::listFilesByQuery(
                $this->query,
                $current,
                config('per_page')
            );
        }

        $this->pagination = new Pagination($items['total'], $current, config('per_page'));

        $this->items = $items['items'];
        $this->total = $items['total'];

        if ($items['total'] <= config('per_page')) {
            $this->pagination = '';
        }

        // if json is requested we send the json items only
        if (AJAX_REQUEST) {
            $layout = new View('admin/files/items.partial');
            foreach (array('items', 'pagination', 'total') as $name) {
                $layout->$name = $this->$name;
            }
            $this->json = array('items' => (string)$layout);
        }

    }
}
