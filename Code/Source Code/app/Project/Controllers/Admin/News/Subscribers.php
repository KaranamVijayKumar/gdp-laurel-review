<?php
/**
 * File: Subscribers.php
 * Created: 02-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\News;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Newsletter;
use Project\Models\NewsletterSubscriber;
use Project\Models\User;
use Project\Services\Newsletter\Postman;
use Project\Support\Newsletter\SubscriberValidator;
use Story\Error;
use Story\NotFoundException;
use Story\Pagination;
use Story\View;

/**
 * Class Subscribers
 * @package Project\Controllers\Admin\News
 */
class Subscribers extends AdminBaseController
{

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('news', 'subscribers');

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
     * @var array
     */
    public $users;

    /**
     * @var NewsletterSubscriber
     */
    public $subscriber;

    /**
     * Shows the page
     */
    public function get()
    {

        $this->title = _('Newsletter Subscribers');
        $this->template = 'admin/newsletter/subscribers';

        $this->query = substr(html2text(trim(get('q', null))), 0, 200);

        $current = (int)get('page', 1);

        // no query, we list the roles by name
        if (!$this->query) {
            $items = NewsletterSubscriber::listSubscribers($current, config('per_page'));
        } else {
            // we have query, get the roles filtered
            $items = NewsletterSubscriber::listSubscribersByQuery(
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
            $layout = new View('admin/newsletter/subscribers.items.partial');
            foreach (array('items', 'pagination', 'total') as $name) {
                $layout->$name = $this->$name;
            }
            $this->json = array('items' => (string)$layout);
        }

    }

    /**
     * Shows the subscriber page
     */
    public function getCreate()
    {
        $this->title = _('Add Subscriber');
        $this->template = 'admin/newsletter/create.subscribers';
        // get the users who doesn't have a subscription
        // get all the users
        $this->users = User::getAllByEmail();

        // Filter the users who have active subscription already
        $subscribed_users = NewsletterSubscriber::lists('email', 'email');


        if (count($subscribed_users)) {
            $this->users = array_diff_key($this->users, $subscribed_users);
        }
    }

    /**
     * Saves the subscriber
     */
    public function postCreate()
    {
        // Since if a user was selected we use that
        $input = $_POST;
        if ($user = post('user')) {
            $input['email'] = $user;
        }
        // Validate the input
        $v = SubscriberValidator::create($input);

        if ($v->validate() && NewsletterSubscriber::createFromForm($v->data())) {
            redirect(
                action('\Project\Controllers\Admin\News\Subscribers'),
                array(
                    'notice' => _('Saved.')
                )
            );
        }

        redirect(
            action('\Project\Controllers\Admin\News\Subscribers@create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );
    }

    /**
     * Shows the subscriber edit page
     *
     * @param int $id
     */
    public function getEdit($id)
    {
        try {

            $this->title = _('Edit Subscriber');
            $this->template = 'admin/newsletter/edit.subscribers';
            $this->subscriber = NewsletterSubscriber::findOrFail((int)$id);

        } catch (NotFoundException $e) {
            redirect(
                action('\Project\Controllers\Admin\News\Subscribers'),
                array(
                    'error' => _('Not found'),
                )
            );
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Updates the subscriber
     *
     * @param int $id
     */
    public function postEdit($id)
    {
        try {

            $this->title = _('Edit Subscriber');
            $this->template = 'admin/newsletter/edit.subscribers';
            $this->subscriber = NewsletterSubscriber::findOrFail((int)$id);

            // Validate the input
            $v = SubscriberValidator::update($_POST, $this->subscriber);

            if ($v->validate() && $this->subscriber->updateFromForm($v->data())) {
                redirect(
                    action('\Project\Controllers\Admin\News\Subscribers'),
                    array(
                        'notice' => _('Saved.')
                    )
                );
            }

            redirect(
                action('\Project\Controllers\Admin\News\Subscribers@edit', array($this->subscriber->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );

        } catch (NotFoundException $e) {
            redirect(
                action('\Project\Controllers\Admin\News\Subscribers'),
                array(
                    'error' => _('Not found'),
                )
            );
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Deletes the selected subscriber
     *
     * @param $id
     */
    public function deleteDelete($id)
    {
        try {
            $this->subscriber = NewsletterSubscriber::findOrFail((int)$id);

            if ($this->subscriber->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\News\Subscribers'),
                    array(
                        'notice' => _('Deleted.')
                    )
                );
            }

        } catch (NotFoundException $e) {
            redirect(
                action('\Project\Controllers\Admin\News\Subscribers'),
                array(
                    'error' => _('Not found'),
                )
            );
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
