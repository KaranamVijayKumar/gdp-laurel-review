<?php
/**
 * File: AbstractPage.php
 * Created: 08-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Html2Text\Html2Text;
use Project\Models\Page;
use Project\Models\PageContent;
use Story\Auth;
use Story\Dispatch;
use Story\NotFoundException;
use Story\Session;
use StoryCart\Cart;

/**
 * Class AbstractPage
 *
 * @package Project\Controllers
 */
abstract class AbstractPage extends BaseController
{
    /**
     * @var PageContent
     */
    public $main_page_content;

    /**
     * @var Page
     */
    public $page;

    /**
     * Constructor
     *
     * @param $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {
        $return = parent::__construct($route, $dispatch);

        return $return;
    }

    /**
     * Builds a page from pages or if not found, builds a temporary page
     *
     * @param array $data
     * @param string $template
     * @param string|null $slug
     */
    protected function buildPageWithFallback(array $data, $template = 'pages/page', $slug = null)
    {

        if (!$slug) {
            $slug = $this->route;
        }

        try {
            $this->buildPage($slug, $template);

        } catch (NotFoundException $e) {

            // if we do not have a page, we create an emtpy page content
            $this->createTempPage(
                $template,
                $data
            );

            $this->createSection($this->main_page_content, 'content');
        }
    }

    /**
     * Builds the page from the pages
     *
     * @param string $slug
     *
     * @param null $template
     * @throws NotFoundException
     */
    protected function buildPage($slug = '', $template = null)
    {

        if (!$slug) {
            $slug = $this->route;
        }
        // attempt to get the page
        $this->page = Page::findBySlug($slug, array('status' => '1'));

        // get the content based on the current locale
        $content = $this->page->getLocalizedContent();

        // we create a page section from each content, and assign the data to it
        $this->pageContentToSections($this->page->data->lists('name', 'value'), $this->page);

        // set the view template
        if (!$template) {
            $this->template = 'pages/' . $this->page->view;
        } else {
            $this->template = $template;
        }

        // Set the main page content
        $this->main_page_content = $content->findBy('name', 'content', new PageContent);
    }

    /**
     * Create page sections from page content parts
     *
     * @param $page_data
     * @param $page
     *
     * @throws \StoryEngine\Exceptions\StoryEngineException
     */
    protected function pageContentToSections(array $page_data, Page $page)
    {

        // We build the page sections for each page content
        foreach ($this->page->content as $page_content) {
            $this->createSection($page_content, null, $page_data, $page);
        }
    }

    /**
     * Creates a page section from page content
     *
     * @param PageContent $page_content
     * @param string $section_name
     * @param array $page_data
     * @param Page $page
     */
    protected function createSection(
        PageContent $page_content,
        $section_name = null,
        $page_data = array(),
        Page $page = null
    )
    {

        if (!$page) {
            $page = new Page;
//            $page->set(array('slug' => $this->route));
        }
        $this->engine->section(
            'page-' . ($section_name ?: $page_content->name),
            function ($section) use ($page_data, $page, $page_content) {

                /** @var  \StoryEngine\Interfaces\SectionInterface $section */
                $section->setTemplateProvider('plain');
                $section->setTemplate($page_content->content);

                // Do we have content text? If not, we generate it from the content
                if (!isset($page_content->content_text)) {
                    $text = new Html2Text($page_content->content);
                    $page_content->content_text = $text->getText();
                }
                $section->appendData(
                    array_merge(
                        $page_data,
                        array(
                            'page' => $page,
                            'content' => $page_content->content,
                            'text' => $page_content->content_text,
                            'title' => $page_content->title
                        )
                    )
                );
            }
        );
    }

    /**
     * Creates a temporary page content
     *
     * @param string $template
     * @param array $attributes
     */
    protected function createTempPage($template, array $attributes = array())
    {

        $this->template = $template;
        $this->main_page_content = new PageContent();
        $this->main_page_content->set($attributes);
    }
}
