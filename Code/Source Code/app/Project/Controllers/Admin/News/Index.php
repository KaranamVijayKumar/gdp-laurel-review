<?php
/**
 * File: Index.php
 * Created: 25-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\News;

use Exception;
use Project\Controllers\Admin\AdminBaseController;
use Project\Models\News;
use Project\Models\NewsContent;
use Project\Models\Newsletter;
use Project\Support\News\Validator;
use Story\Collection;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;
use Story\Pagination;
use Story\View;

/**
 * Class Index
 *
 * @package Project\Controllers\Admin\News
 */
class Index extends AdminBaseController
{

    /**
     * @var News
     */
    public $article;

    /**
     * @var Collection
     */
    public $items;

    /**
     * Optional editable sections
     *
     * @var array
     */
    public $optional_sections = array();

    /**
     * @var Pagination
     */
    public $pagination;

    /**
     * @var string
     */
    public $query;

    /**
     * Required editable sections
     *
     * @var array
     */
    public $required_sections = array('content');

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('news');

    /**
     * @var string
     */
    public $template = 'admin/news/index';

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
        include_once SP . 'Project/Support/News/news_helpers.php';
        parent::__construct($route, $dispatch);
    }

    /**
     * Shows the delete article page
     * @param $id
     */
    public function deleteDelete($id)
    {
        $db = load_database();

        try {
            $db->pdo->beginTransaction();

            $this->article = News::findOrFail($id);

            $this->article->deleteWithContent();

            $db->pdo->commit();

            redirect(
                action('\Project\Controllers\Admin\News\Index'),
                array(
                    'notice' => _('Deleted.'),
                )
            );
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\News\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            if ($db->pdo->inTransaction()) {
                $db->pdo->rollBack();
            }
            Error::exception($e);
        }
    }

    /**
     * Shows the news list
     */
    public function get()
    {
        $this->title = _('News');
        $this->query = substr(html2text(trim(get('q', null))), 0, 200);

        $current = (int)get('page', 1);

        // no query, we list the roles by name
        if (!$this->query) {
            $items = News::listArticles($current, config('per_page'));
        } else {
            // we have query, get the roles filtered
            $items = News::listArticlesByQuery($this->query, $current, config('per_page'));
        }

        $this->pagination = new Pagination($items['total'], $current, config('per_page'));

        $this->items = $items['items'];
        $this->total = $items['total'];

        if ($items['total'] <= config('per_page')) {
            $this->pagination = '';
        }

        // if json is requested we send the json items only
        if (AJAX_REQUEST) {
            $layout = new View('admin/news/_partials/items');
            $layout->items = $this->items;
            $layout->pagination = $this->pagination;
            $layout->total = $this->total;
            $this->json = array('items' => (string)$layout);
        }
    }

    /**
     * Shows the create page
     */
    public function getCreate()
    {
        list($this->required_sections, $this->optional_sections) = array_values(get_news_sections());

        $this->title = _('New article');
        $this->template = 'admin/news/create';
    }

    /**
     * Shows the edit article page
     *
     * @param $id
     */
    public function getEdit($id)
    {
        try {
            $this->article = News::findOrFail((int)$id);

            // get the content based on the current locale
            $content = $this->article->getLocalizedContent(app('locale'));


            $sections = array();
            foreach ($content as $item) {
                $name = $item->name;
                $sections[$name] = $item;
            }

            $this->article->set(array('sections' => $sections));

            list($this->required_sections, $this->optional_sections) = array_values(get_news_sections());

            $this->title = _('Edit article');
            $this->template = 'admin/news/edit';
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\News\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Creates a new news article
     */
    public function postCreate()
    {
        // filter the input
        $input = filter_news_input_fields($_POST);

        // create the validator
        $validator = Validator::create($input);

        // validate
        if ($validator->validate()) {
            // save
            $db = load_database();


            try {
                $db->pdo->beginTransaction();

                $title = $input['title'];

                // insert article
                $article = News::createArticle($title, $input);
                // insert content
                NewsContent::createArticleContent($article, $title, $input, app('locale'));
                // newsletter
                if ($input['newsletter'] && $input['status']) {
                    Newsletter::createFromCreatedNews($article, $input, app('locale'));
                }

                $db->pdo->commit();
                event('news.article.created', $article);

                redirect(action('\Project\Controllers\Admin\News\Index'), array('notice' => _('Created.')));
            } catch (Exception $e) {
                $db->pdo->rollBack();
                Error::exception($e);
            }
        }

        redirect(
            action('\Project\Controllers\Admin\News\Index@create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $validator->errorsToNotification(),
            )
        );
    }

    /**
     * Saves the existing article
     *
     * @param $id
     */
    public function postEdit($id)
    {
        $db = load_database();

        try {
            $this->article = News::findOrFail($id);

            // filter the input
            $input = filter_news_input_fields($_POST);
            $input['slug'] = slug($input['title']);

            // create the validator
            $validator = Validator::edit($input, $this->article);


            // if the validation passes we save the article and it's contents
            if ($validator->validate()) {
                $db->pdo->beginTransaction();

                $this->article->updateArticle($input, app('locale'));

                $db->pdo->commit();

                redirect(
                    action('\Project\Controllers\Admin\News\Index@edit', array($this->article->id)),
                    array(
                        'notice' => _('Saved.'),
                    )
                );
            }


            redirect(
                action('\Project\Controllers\Admin\News\Index@edit', array($this->article->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $validator->errorsToNotification(),
                )
            );
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\News\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            if ($db->pdo->inTransaction()) {
                $db->pdo->rollBack();
            }
            Error::exception($e);
        }
    }
}
