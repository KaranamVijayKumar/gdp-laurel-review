<?php
/**
 * File: Index.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Exporters;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Export;
use Story\Collection;
use Story\Pagination;
use Story\View;

/**
 * Class Index
 * @package Project\Controllers\Admin\Exporters
 */
class Index extends AdminBaseController
{
    /**
     * @var array
     */
    public $selected = array('preferences', 'exporters');

    /**
     * @var Collection
     */
    public $items;

    /**
     * @var
     */
    public $pagination;

    /**
     * @var
     */
    public $query;

    /**
     * @var string
     */
    public $template = 'admin/exporters/index';

    /**
     * @var
     */
    public $title;

    /**
     * @var
     */
    public $total;

    /**
     * Shows the current exporters
     */
    public function get()
    {
        $this->title = _('Exporters');
        $this->query = trim(get('q', null));

        $current = (int)get('page', 1);

        // no query, we list the exports by name
        if (!$this->query) {
            $items = Export::listExports($current, config('per_page'));
        } else {
            // we have query, get the exports filtered
            $items = Export::listExportsByQuery($this->query, $current, config('per_page'));
        }

        $this->pagination = new Pagination($items['total'], $current, config('per_page'));

        $this->items = $items['items'];
        $this->total = $items['total'];


        if ($items['total'] <= config('per_page')) {
            $this->pagination = '';
        }

        // if json is requested we send the json items only
        if (AJAX_REQUEST) {
            $layout = new View('admin/exporters/items.partial');

            $layout->items = $this->items;

            $layout->pagination = $this->pagination;

            $layout->total = $this->total;
            $this->json = array('items' => (string)$layout);
        }
    }
}
