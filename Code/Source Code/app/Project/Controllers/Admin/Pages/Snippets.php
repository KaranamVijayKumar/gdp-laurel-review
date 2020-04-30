<?php
/**
 * File: Snippets.php
 * Created: 25-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Snippet;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;
use Story\Pagination;
use Story\View;

/**
 * Class Snippets
 * @package Project\Controllers\Admin\Pages
 */
class Snippets extends AdminBaseController
{

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('pages', 'snippets');

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
    public $template = 'admin/snippets/index';

    /**
     * @var Snippet
     */
    public $snippet;

    /**
     * Shows the snippet list
     */
    public function get()
    {

        $this->title = _('Snippets');

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);
        $current = (int)get('page', 1);

        // no query, we list submissions by name
        if (!$this->query) {
            $items = Snippet::listSnippets(
                $current,
                config('per_page')
            );
        } else {
            // we have query, get the submissions filtered
            $items = Snippet::listSnippetsByQuery(
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
            $layout = new View('admin/snippets/items.partial');
            foreach (array('items', 'pagination', 'total') as $name) {
                $layout->$name = $this->$name;
            }
            $this->json = array('items' => (string)$layout);
        }

    }


    /**
     * Shows the snippet creation page
     *
     */
    public function getCreate()
    {
        $this->template = 'admin/snippets/create';
        $this->title = _('New Snippet');
    }

    /**
     * Creates the new snippet
     */
    public function postCreate()
    {
        /** @var \Story\Validator|Snippet $result */
        if (($result = Snippet::createFromForm($_POST)) === true) {
            redirect(action('\Project\Controllers\Admin\Pages\Snippets'), array('notice' => _('Created.')));
        }
        redirect(
            action('\Project\Controllers\Admin\Pages\Snippets@create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $result->errorsToNotification(),
            )
        );
    }

    /**
     * Shows the edit snippet page
     *
     * @param $id
     */
    public function getEdit($id)
    {
        try {
            $this->snippet = Snippet::findOrFail((int)$id);

            $this->title = _('Snippet');

            $this->template = 'admin/snippets/edit';

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Snippets'),
                array(
                    'error' => _('Snippet not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Updates the snippet
     *
     * @param $id
     */
    public function postEdit($id)
    {
        try {
            $this->snippet = Snippet::findOrFail((int)$id);

            /** @var \Story\Validator|Snippet $result */
            if (($result = $this->snippet->updateFromForm($_POST)) === true) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Snippets'),
                    array('notice' => _('Saved.'))
                );
            }
            // If there were validation errors we redirect back
            redirect(
                action('\Project\Controllers\Admin\Pages\Snippets@edit', array($this->snippet->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $result->errorsToNotification(),
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Snippets'),
                array(
                    'error' => _('Snippet not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Deletes a specific snippet
     *
     * @param $id
     */
    public function deleteDelete($id)
    {
        try {
            $this->snippet = Snippet::findOrFail((int)$id);

            if ($this->snippet->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Snippets'),
                    array('notice' => _('Deleted.'))
                );
            }

            redirect(
                action('\Project\Controllers\Admin\Pages\Snippets@edit', array($this->snippet->id)),
                array(
                    'error'      => 'There was a problem deleting the snippet.',
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Snippets'),
                array(
                    'error' => _('Snippet not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
