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
use Project\Models\Podcasts;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;
use Story\Pagination;
use Story\View;

/**
 * Class Snippets
 * @package Project\Controllers\Admin\Pages
 */
class Podcast extends AdminBaseController
{

    /**
     * Selected menus
     *
     * @var array
     */
	 const PODCAST_IMG_PATH = 'uploads/podcast/';
   // const PODCAST_IMG_PATH = 'storage/files/podcast/';
    public $selected = array('pages', 'podcast');

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
    public $template = 'admin/podcast/index';

    /**
     * @var Snippet
     */
    public $snippet;

    /**
     * Shows the snippet list
     */
    public function get()
    {

        $this->title = _('Podcast');

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);
        $current = (int)get('page', 1);

        // no query, we list submissions by name
        if (!$this->query) {
            $items = Podcasts::listSnippets(
                $current,
                config('per_page')
            );
        } else {
            // we have query, get the submissions filtered
            $items = Podcasts::listSnippetsByQuery(
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
            $layout = new View('admin/podcast/items.partial');
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
        $this->template = 'admin/podcast/create';
        $this->title = _('New Snippet');
    }

    /**
     * Creates the new snippet
     */
    public function postCreate()
    {
        /** @var \Story\Validator|Snippet $result */
        if (($result = Podcasts::createFromForm($_POST)) === true) {
            redirect(action('\Project\Controllers\Admin\Pages\Podcast'), array('notice' => _('Created.')));
        }
        redirect(
            action('\Project\Controllers\Admin\Pages\Podcast@create'),
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
            $this->snippet = Podcasts::findOrFail((int)$id);

            $this->title = _('Podcast');

            $this->template = 'admin/podcast/edit';

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Podcast'),
                array(
                    'error' => _('Podcast not found.')
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
            $this->snippet = Podcasts::findOrFail((int)$id);

            /** @var \Story\Validator|Snippet $result */
            if (($result = $this->snippet->updateFromForm($_POST)) === true) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Podcast'),
                    array('notice' => _('Saved.'))
                );
            }
            // If there were validation errors we redirect back
            redirect(
                action('\Project\Controllers\Admin\Pages\Podcast@edit', array($this->snippet->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error' => $result->errorsToNotification(),
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Podcast'),
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
            echo action('\Project\Controllers\Admin\Pages\Podcast');
            
            $this->snippet = Podcasts::findOrFail((int)$id);
            /*Added lines for deletiing file which created podcast time*/
            $podcast = Podcasts::findOrFail((int)$id);
            $myArray = json_decode(json_encode($podcast), true);
            unlink(static::PODCAST_IMG_PATH . $myArray['attributes']['profile_img_path']);
            unlink(static::PODCAST_IMG_PATH . $myArray['attributes']['audio_img_path']);


            if ($this->snippet->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Podcast'),
                    array('notice' => _('Deleted.'))
                );
            }

            redirect(
                action('\Project\Controllers\Admin\Pages\Podcast@edit', array($this->snippet->id)),
                array(
                    'error' => 'There was a problem deleting the Podcast.',
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Podcast'),
                array(
                    'error' => _('Podcast not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
