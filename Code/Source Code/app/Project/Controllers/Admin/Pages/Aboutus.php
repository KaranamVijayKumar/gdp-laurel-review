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
use Project\Models\Aboutuss;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;
use Story\Pagination;
use Story\View;

/**
 * Class Snippets
 * @package Project\Controllers\Admin\Pages
 */
class Aboutus extends AdminBaseController
{

    /**
     * Selected menus
     *
     * @var array
     */
    const ABOUTUS_IMG_PATH = 'uploads/aboutus/';
    public $selected = array('pages', 'Hostname');

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
    public $template = 'admin/aboutus/index';

    /**
     * @var Snippet
     */
    public $snippet;

    /**
     * Shows the snippet list
     */
    public function get()
    {

        $this->title = _('Host Name');

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);
        $current = (int)get('page', 1);

        // no query, we list submissions by name
        if (!$this->query) {
            $items = Aboutuss::listSnippets(
                $current,
                config('per_page')
            );
        } else {
            // we have query, get the submissions filtered
            $items = Aboutuss::listSnippetsByQuery(
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
            $layout = new View('admin/aboutus/items.partial');
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
        $this->template = 'admin/aboutus/create';
        $this->title = _('New Hostname');
    }

    /**
     * Creates the new snippet
     */
    public function postCreate()
    {

        /** @var \Story\Validator|Snippet $result */
        if (($result = Aboutuss::createFromForm($_POST)) === true) {
            redirect(action('\Project\Controllers\Admin\Pages\Aboutus'), array('notice' => _('Created.')));
        }
        redirect(
            action('\Project\Controllers\Admin\Pages\Aboutus@create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error' => $result->errorsToNotification(),
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
            $this->snippet = Aboutuss::findOrFail((int)$id);

            $this->title = _('Podcast');

            $this->template = 'admin/aboutus/edit';

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Aboutus'),
                array(
                    'error' => _('Host Name not found.')
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
            $this->snippet = Aboutuss::findOrFail((int)$id);

            /** @var \Story\Validator|Snippet $result */
            if (($result = $this->snippet->updateFromForm($_POST)) === true) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Aboutus'),
                    array('notice' => _('Saved.'))
                );
            }
            // If there were validation errors we redirect back
            redirect(
                action('\Project\Controllers\Admin\Pages\Aboutus@edit', array($this->snippet->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error' => $result->errorsToNotification(),
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Aboutus'),
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
            echo action('\Project\Controllers\Admin\Pages\Aboutus');
            
            $this->snippet = Aboutuss::findOrFail((int)$id);
            /*Added lines for deletiing file which created podcast time*/
            $podcast = Aboutuss::findOrFail((int)$id);
            $myArray = json_decode(json_encode($podcast), true);
            unlink(static::ABOUTUS_IMG_PATH . $myArray['attributes']['profile_img_path']);
            unlink(static::ABOUTUS_IMG_PATH . $myArray['attributes']['audio_img_path']);


            if ($this->snippet->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Aboutus'),
                    array('notice' => _('Deleted.'))
                );
            }

            redirect(
                action('\Project\Controllers\Admin\Pages\Aboutus@edit', array($this->snippet->id)),
                array(
                    'error' => 'There was a problem deleting the Hostname.',
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Aboutus'),
                array(
                    'error' => _('HostName not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
