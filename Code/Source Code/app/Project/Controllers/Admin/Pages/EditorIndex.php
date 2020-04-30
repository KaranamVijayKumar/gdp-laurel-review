<?php
/**
 * File: EditorIndex.php
 * Created: 01-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Page;
use Story\URL;

/**
 * Class EditorIndex
 * @package Project\Controllers\Admin\Pages
 */
class EditorIndex extends AdminBaseController
{
    /**
     *
     */
    public function get()
    {
        $this->json = Page::getPageList(1000, function ($items) {

            $pages = array(
                array('name' => _('Select a page...'), 'url' => '')
            );
            /** @var Page $item */
            foreach ($items['items'] as $item) {
                $pages[] = array(
                    'name' => $item->title ?: $item->slug,
                    'url'  => URL::to($item->slug),
                );
            }

            return $pages;
        });
    }
}
