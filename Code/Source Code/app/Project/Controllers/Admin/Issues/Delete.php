<?php
/**
 * File: Delete.php
 * Created: 06-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;
use Project\Models\IssueFile;
use Story\Error;
use Story\NotFoundException;

class Delete extends AdminBaseController
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
    public $template = 'admin/issues/delete';

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

        $this->title = _('Delete Issue');

        // get the issue and the related content and file
        try {

            $this->issue = Issue::findOrFail((int) $id);

            // get the issue file
            $this->issue->cover_image = IssueFile::one(
                array(
                    'issueable_id' => $this->issue->id,
                    'issueable_type' => get_class($this->issue)
                )
            );
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Deletes the issue
     *
     * @param $id
     */
    public function post($id)
    {

        // get the issue and the related content and file
        try {

            $this->issue = Issue::findOrFail((int) $id);

            // get the issue file
            $this->issue->cover_image = IssueFile::one(
                array(
                    'issueable_id' => $this->issue->id,
                    'issueable_type' => get_class($this->issue)
                )
            );

            if ($this->issue->deleteWithFiles()) {
                redirect(
                    action('\Project\Controllers\Admin\Issues\Index'),
                    array(
                        'notice' => _('Deleted.'),
                    )
                );
            }
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
