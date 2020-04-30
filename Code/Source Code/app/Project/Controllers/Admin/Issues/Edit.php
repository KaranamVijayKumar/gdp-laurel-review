<?php
/**
 * File: Edit.php
 * Created: 01-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;
use Project\Models\IssueContent;
use Project\Models\IssueFile;
use Project\Services\Issues\IssueValidator;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Edit
 *
 * @package Project\Controllers\Admin\Issues
 */
class Edit extends AdminBaseController
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
    public $template = 'admin/issues/edit';

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

        $this->title = _('Issue Properties');

        // get the issue and the related content and file
        try {

            $this->issue = Issue::findOrFail((int) $id);

            // load the issue contents
            $this->issue->contents->load();

            // get the issue file
            $this->issue->cover_image = IssueFile::one(
                array(
                    'issueable_id' => $this->issue->id,
                    'issueable_type' => get_class($this->issue)
                )
            );


            $this->issue->default_content = new IssueContent();
            $this->issue->default_content->set(array('content' => ''));
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * @param $id
     */
    public function post($id)
    {
        try {
            $this->issue = Issue::findOrFail((int) $id);

            // load the issue contents
            $this->issue->contents->load();

            // issue cover image
            $this->issue->cover_image = IssueFile::one(
                array(
                    'issueable_id' => $this->issue->id,
                    'issueable_type' => get_class($this->issue)
                )
            );

            // validate the user data
            $v = new IssueValidator($_POST);
            $v->addEditRules($this->issue);

            // if valid, we update the issue, contents and file
            if ($v->validate() && ($issue = $this->issue->updateIssueFromForm($v->data()))) {

                // redirect to the issue edit page
                event('issue.edited', $issue);

                redirect(
                    action('\Project\Controllers\Admin\Issues\Edit', array($this->issue->id)),
                    array('notice' => _('Saved.'), '__fields' => array())
                );
            }

            // redirect to the prev page and display the errors
            redirect(
                action('\Project\Controllers\Admin\Issues\Edit', array($this->issue->id)),
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
