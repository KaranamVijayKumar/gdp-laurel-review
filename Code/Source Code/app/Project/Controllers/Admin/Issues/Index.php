<?php
/**
 * File: Index.php
 * Created: 25-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;

use Story\Dispatch;
use Story\Pagination;

use Story\View;

/**
 * Class Index
 *
 * @package Project\Controllers\Admin\Issues
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
    public $selected = array('issues');

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/issues/index';

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
     * Shows the issues
     */
    public function get()
    {

        $this->title = _('Issues');
        $this->query = substr(html2text(trim(get('q', null))), 0, 200);

        $current = (int)get('page', 1);

        // no query, we list the roles by name
        if (!$this->query) {
            $items = Issue::listIssues($current, config('per_page'));
        } else {
            // we have query, get the roles filtered
            $items = Issue::listIssuesByQuery($this->query, $current, config('per_page'));
        }

        $this->pagination = new Pagination($items['total'], $current, config('per_page'));

        $this->items = $items['items'];
        $this->total = $items['total'];

        if ($items['total'] <= config('per_page')) {
            $this->pagination = '';
        }

        // if json is requested we send the json items only
        if (AJAX_REQUEST) {
            $layout = new View('admin/issues/partials/items');

            $layout->items = $this->items;
            
            $layout->pagination = $this->pagination;

            $layout->total = $this->total;
            $this->json = array('items' => (string)$layout);
        }
    }
}
