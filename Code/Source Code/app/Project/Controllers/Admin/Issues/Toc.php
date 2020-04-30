<?php
/**
 * File: Toc.php
 * Created: 02-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;
use Project\Models\IssueToc;
use Project\Services\Issues\IssueTocValidator;
use Story\Error;
use Story\NotFoundException;

class Toc extends AdminBaseController
{
    /**
     * @var Issue
     */
    public $issue;

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
    public $template = 'admin/issues/toc';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the issue creation page
     *
     * @param int $id
     */
    public function get($id)
    {

        $this->title = _('Issue TOC');

        // get the issue and the related content and file
        try {

            $this->issue = Issue::findOrFail((int) $id);

            // get the toc for the issue and load the titles
            $this->issue->related('toc', array(), 0, 0, array('order' => 'asc'))->loadWithTitles();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Saves the issue toc
     *
     * @param $id
     */
    public function post($id)
    {
        try {

            $this->issue = Issue::findOrFail((int) $id);

            // validate the user data
            $v = new IssueTocValidator($_POST);
            $v->addRules($this->issue);

            // if valid, we update the issue, contents and file
            if ($v->validate() && (IssueToc::updateTocForIssueFromForm($this->issue, ($v->data())))) {

                // redirect to the issue edit page
                event('issue.toc.updated', $this->issue);

                redirect(
                    action('\Project\Controllers\Admin\Issues\Toc', array($this->issue->id)),
                    array('notice' => _('Saved.'), '__fields' => array())
                );
            }

            // redirect to the prev page and display the errors
            redirect(
                action('\Project\Controllers\Admin\Issues\Toc', array($this->issue->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {

            Error::exception($e);
        }
    }
}
