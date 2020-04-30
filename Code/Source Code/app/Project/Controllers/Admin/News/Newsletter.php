<?php
/**
 * File: Newsletter.php
 * Created: 25-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\News;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Newsletter as NewsletterRepository;
use Project\Support\Newsletter\Validator;
use Story\Collection;
use Story\Error;
use Story\NotFoundException;
use Story\Pagination;
use Story\View;

/**
 * Class Newsletter
 * @package Project\Controllers\Admin\News
 */
class Newsletter extends AdminBaseController
{

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('news', 'newsletter');

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * @var Collection
     */
    public $items;

    /**
     * @var Pagination
     */
    public $pagination;

    /**
     * @var string
     */
    public $query;

    /**
     * @var int
     */
    public $total;

    /**
     * @var array
     */
    public $templateList = array();

    /**
     * @var bool
     */
    public $editable = false;

    /**
     * @var \Project\Models\Newsletter
     */
    public $newsletter;

    /**
     * Shows the newsletter list
     */
    public function get()
    {

        $this->title = _('Newsletter');
        $this->template = 'admin/newsletter/index';

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);

        $current = (int)get('page', 1);

        // no query, we list the roles by name
        if (!$this->query) {
            $items = NewsletterRepository::listNewsletters($current, config('per_page'));
        } else {
            // we have query, get the roles filtered
            $items = NewsletterRepository::listNewslettersByQuery(
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
            $layout = new View('admin/newsletter/items.partial');
            foreach (array('items', 'pagination', 'total') as $name) {
                $layout->$name = $this->$name;
            }
            $this->json = array('items' => (string)$layout);
        }
    }

    /**
     * Shows the newsletter creation template
     */
    public function getCreate()
    {
        $this->title = _('Create Newsletter');
        $this->template = 'admin/newsletter/create';
        $this->editable = true;
        $this->templateList = NewsletterRepository::getTemplates();
    }

    /**
     * Saves the newly created newsletter
     */
    public function postCreate()
    {
        $v = Validator::create($_POST);

        if ($v->validate() && NewsletterRepository::createFromForm($_POST)) {

            redirect(
                action('\Project\Controllers\Admin\News\Newsletter'),
                array(
                    'notice' => _('Created.'),
                )
            );

        }
        redirect(
            action('\Project\Controllers\Admin\News\Newsletter@create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );
    }


    /**
     * @param $id
     */
    public function getEdit($id)
    {
        try {
            $this->template = 'admin/newsletter/edit';
            // load the newsletter and it's content
            $this->newsletter = NewsletterRepository::findOrFail((int)$id);
            $this->newsletter->content->load();
            // get the templates
            $this->templateList = NewsletterRepository::getTemplates();
            // set the editable flag
            $this->editable = $this->newsletter->isEditable();

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\News\Newsletter'),
                array(
                    'error' => _('Not found.'),
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Updates the newsletter
     *
     * @param $id
     */
    public function postEdit($id)
    {
        try {
            // load the newsletter and it's content
            $this->newsletter = NewsletterRepository::findOrFail((int)$id);
            // redirect of not editable
            $this->redirectIfNotEditable($this->newsletter);

            // validate and save
            $v = Validator::update($_POST);
            if ($v->validate() && $this->newsletter->updateFromForm($v->data())) {
                redirect(
                    action('\Project\Controllers\Admin\News\Newsletter'),
                    array(
                        'notice' => _('Saved.'),
                    )
                );
            }

            redirect(
                action('\Project\Controllers\Admin\News\Newsletter@edit', array($this->newsletter->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\News\Newsletter'),
                array(
                    'error' => _('Not found.'),
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Deletes an editable newsletter
     *
     * @param $id
     */
    public function deleteDelete($id)
    {
        try {
            // load the newsletter and it's content
            $this->newsletter = NewsletterRepository::findOrFail((int)$id);
            // redirect if not editable
            $this->redirectIfNotEditable($this->newsletter);

            // delete it
            if ($this->newsletter->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\News\Newsletter'),
                    array(
                        'notice' => _('Deleted.'),
                    )
                );
            }
        } catch (NotFoundException $e) {
            redirect(
                action('\Project\Controllers\Admin\News\Newsletter'),
                array(
                    'error' => _('Not found.'),
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }

    /**
     * Redirects to the newsletter list if the newsletter is not editable
     *
     * @param NewsletterRepository $newsletter
     * @return bool
     */
    public function redirectIfNotEditable(\Project\Models\Newsletter $newsletter)
    {
        if (!$newsletter->isEditable()) {
            redirect(
                action('\Project\Controllers\Admin\News\Newsletter'),
                array(
                    'errorTitle' => _('Could not edit.'),
                    'error'      => _('Newsletter was already sent.'),
                )
            );

        }
    }
}
