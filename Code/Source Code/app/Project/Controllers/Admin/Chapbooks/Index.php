<?php
/**
 * File: Index.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Chapbooks;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Chapbook;
use Story\Dispatch;
use Story\Pagination;
use Story\View;

/**
 * Class Index
 *
 * @package Project\Controllers\Admin\Chapbooks
 */
class Index extends AdminBaseController
{

    /**
     * @var \Story\Collection
     */
    public $items;

    /**
     * @var \Story\Pagination
     */
    public $pagination;

    /**
     * @var string
     */
    public $query;

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('issues', 'chapbooks');

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/chapbooks/index';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $total;

    /**
     * @var array
     */
    protected $project;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        $this->project = app('project');
        parent::__construct($route, $dispatch);
    }

    /**
     * Shows the chapbooks
     */
    public function get()
    {

        $this->title = _('Chapbooks');
        $this->query = substr(html2text(trim(get('q', null))), 0, 200);

        $current = (int)get('page', 1);

        // no query, we list the roles by name
        if (!$this->query) {
            $items = Chapbook::listPublications($current, config('per_page'));
        } else {
            // we have query, get the roles filtered
            $items = Chapbook::listByQuery($this->query, $current, config('per_page'));
        }

        $this->pagination = new Pagination($items['total'], $current, config('per_page'));

        $this->items = $items['items'];
        $this->total = $items['total'];

        if ($items['total'] <= config('per_page')) {
            $this->pagination = '';
        }

        // if json is requested we send the json items only
        if (AJAX_REQUEST) {
            $layout = new View('admin/chapbooks/partials/items');

            $layout->items = $this->items;

            $layout->pagination = $this->pagination;

            $layout->total = $this->total;
            $this->json = array('items' => (string)$layout);
        }
    }
}
