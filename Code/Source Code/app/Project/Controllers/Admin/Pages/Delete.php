<?php
/**
 * File: Delete.php
 * Created: 30-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Page;
use Story\Error;
use Story\NotFoundException;

class Delete extends AdminBaseController
{

    /**
     * @var Page
     */
    public $page;

    /**
     * Shows the page edit view
     *
     * @param $id
     */
    public function post($id)
    {
        try {

            $this->page = Page::findOrFail((int)$id);

            if ($this->page->locked) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Edit', array($this->page->id)),
                    array(
                        'errorTitle' => _('Cannot delete'),
                        'error' => _('Page is locked.')
                    )
                );
            }

            if ($this->page->delete()) {

                redirect(
                    action('\Project\Controllers\Admin\Pages\Index'),
                    array(
                        'notice' => _('Deleted.')
                    )
                );

            }

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
}
