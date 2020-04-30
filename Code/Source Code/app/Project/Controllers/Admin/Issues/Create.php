<?php
/**
 * File: Create.php
 * Created: 29-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;
use Project\Services\Issues\IssueValidator;

class Create extends AdminBaseController
{

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
    public $template = 'admin/issues/create';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the issue creation page
     */
    public function get()
    {

        $this->title = _('New Issue');
    }

    public function post()
    {

        $v = new IssueValidator($_POST);
        $v->addCreateRules();
        // validate the post data && create the issue
        /** @var Issue $issue */
        if ($v->validate() && ($issue = Issue::createIssueFromForm($v->data()))) {

            // redirect to the issue toc page
            event('issue.created', $issue);

            redirect(
                action('\Project\Controllers\Admin\Issues\Show', array($issue->id)),
                array('notice' => _('Created.'))
            );
        }

        // redirect to the prev page and display the errors
        redirect(
            action('\Project\Controllers\Admin\Issues\Create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );
    }
}
