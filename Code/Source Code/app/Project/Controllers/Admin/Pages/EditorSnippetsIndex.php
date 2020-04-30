<?php
/**
 * File: EditorSnippetsIndex.php
 * Created: 01-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Snippet;

/**
 * Class EditorSnippetsIndex
 * @package Project\Controllers\Admin\Pages
 */
class EditorSnippetsIndex extends AdminBaseController
{
    /**
     *
     */
    public function get()
    {
        $this->json = Snippet::getSnippetList(1000, function ($items) {

            $snippets = array();
            /** @var Snippet $item */
            foreach ($items['items'] as $item) {
                $snippets[] = array(
                    'title' => $item->slug,
                    'content'  => $item->attributes['content'],
                );
            }

            return $snippets;
        });

    }
}
