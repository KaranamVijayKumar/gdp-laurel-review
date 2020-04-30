<?php
/**
 * File: Index.php
 * Created: 25-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Page;
use Story\Pagination;
use Story\View;

/**
 * Class Index
 * @package Project\Controllers\Admin\Pages
 */
class Index extends AdminBaseController
{

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('pages');

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
     * @var Pagination
     */
    public $pagination;

    /**
     * @var \Story\Collection
     */
    public $items;

    /**
     * @var int
     */
    public $total;

    /**
     * @var string
     */
    public $template = 'admin/pages/index';

    /**
     * Shows the page
     */
    public function get()
    {

        $this->title = _('Pages');

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);
        $current = (int)get('page', 1);

        // no query, we list submissions by name
        if (!$this->query) {
            $items = Page::listPages(
                $current,
                config('per_page')
            );
        } else {
            // we have query, get the submissions filtered
            $items = Page::listPagesByQuery(
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
            $layout = new View('admin/pages/items.partial');
            foreach (array('items', 'pagination', 'total') as $name) {
                $layout->$name = $this->$name;
            }
            $this->json = array('items' => (string)$layout);
        }
    }
}
