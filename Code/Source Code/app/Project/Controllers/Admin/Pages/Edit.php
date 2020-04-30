<?php
/**
 * File: Edit.php
 * Created: 27-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Page;
use Project\Models\PageContent;
use Project\Models\PageMeta;
use Project\Support\Pages\Validator;
use Story\Collection;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Edit
 * @package Project\Controllers\Admin\Pages
 */
class Edit extends AdminBaseController
{

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $selected = array('pages');

    /**
     * @var Page
     */
    public $page;

    /**
     * @var string
     */
    public $template = 'admin/pages/edit';

    /**
     * @var Collection
     */
    public $page_content;

    /**
     * @var array
     */
    public $required_sections = array();

    /**
     * @var array
     */
    public $optional_sections = array();

    /**
     * @var PageContent
     */
    public $default_content;

    /**
     * Constructor
     *
     * @param $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {
        require_once SP . 'Project/Support/Pages/pages_helpers.php';
        $this->default_content = new PageContent(array('content' => '', 'title' => ''));
        parent::__construct($route, $dispatch);
    }

    /**
     * Shows the page edit view
     *
     * @param $id
     */
    public function get($id)
    {
        try {
            $this->title = _('Edit page');

            $this->page = Page::findOrFail((int)$id);


            // get the page content
            $this->page->content->load();
            $this->page_content = $this->page->getLocalizedContent('en', false);

            if (!count($this->page_content)) {
                $this->page_content = new Collection;
            }

            // get the page meta and sort by name asc
            $this->page->related('meta', null, 0, 0, array('name' => 'asc'))->load();

            // if we have custom meta added we add those to the page meta
            $this->insertCustomMeta();


            // Add the page sections, these can help create the content tabs
            list($this->required_sections, $this->optional_sections) = array_values(get_pages_sections());


        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Pages\Index'),
                array(
                    'error' => _('Page not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Saves the entire page
     * @param $id
     */
    public function post($id)
    {

        try {

            $this->page = Page::findOrFail((int)$id);

            // Validate the entry
            $v = Validator::update($_POST, $this->page);

            if ($v->validate() && $this->page->updateFromForm($v->data())) {

                redirect(
                    action('\Project\Controllers\Admin\Pages\Edit', array($this->page->id)),
                    array(
                        'notice' => _('Saved.'),
                    )
                );

            }

            redirect(
                action('\Project\Controllers\Admin\Pages\Edit', array($this->page->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );


        } catch (NotFoundException $e) {

            $this->json = array('error' => _('Page not found.'));

        } catch (\Exception $e) {
            $this->json = array('error' => $e->getMessage());
            Error::exception($e);
        }

        // validate the data

        // if valid && update page, redirect to edit
    }

    /**
     * Saves the page slug
     * @param $id
     * @return bool
     */
    public function postSlug($id)
    {
        try {

            $this->page = Page::findOrFail((int)$id);

            if ($this->page->locked) {
                $this->json = array('error' => _('Page is locked.'));

                return true;
            }

            // Validate the entry
            $v = Validator::editSlug($_POST, $this->page);

            if ($v->validate()) {
                $data = $v->data();

                $this->page->updateSlug($data['value']);

                $this->json = array('value' => $this->page->slug);

            } else {
                $errors = '';
                foreach ($v->errors() as $error) {
                    $error = array_unique($error);
                    $errors .= current($error);
                }

                $this->json = array(
                    'error' => $errors

                );
            }

        } catch (NotFoundException $e) {

            $this->json = array('error' => _('Page not found.'));

        } catch (\Exception $e) {
            $this->json = array('error' => $e->getMessage());
            Error::exception($e);

            return false;
        }

        return true;
    }

    /**
     * Saves the page title
     * @param $id
     */
    public function postTitle($id)
    {

        try {

            $this->page = Page::findOrFail((int)$id);

            // Validate the entry
            $v = Validator::editTitle($_POST, $this->page);

            if ($v->validate()) {

                $data = $v->data();

                PageContent::updateTitleForPage($this->page, $data['value']);
                $this->json = array('value' => $data['value']);

            } else {
                $errors = '';
                foreach ($v->errors() as $error) {
                    $error = array_unique($error);
                    $errors .= current($error);
                }

                $this->json = array(
                    'error' => $errors

                );
            }

        } catch (NotFoundException $e) {

            $this->json = array('error' => _('Page not found.'));

        } catch (\Exception $e) {
            $this->json = array('error' => $e->getMessage());
            Error::exception($e);
        }
    }

    /**
     * Inserts the user defined custom meta when the user entered some extra meta elements
     */
    protected function insertCustomMeta()
    {

        /** @var \Story\Session $session */
        $session = app('session');

        if ($session->get('__errors')) {
            $fields = $session->get('__fields', array());

            PageMeta::pushCustomMeta($this->page, $fields);
        }

    }
}
