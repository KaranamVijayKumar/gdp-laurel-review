<?php
/**
 * File: IndexController.php
 * Created: 29-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Project\Models\Page;


/**
 * Class IndexController
 *
 * @package Project\Controllers
 */
class IndexController extends AbstractPage
{

    /**
     * Shows the index page
     */
    public function get()
    {

        $title = sprintf(_('Welcome to %s'), $this->app['config']['title']);

        $this->buildPageWithFallback(
            array(
                'title' => $title,
                'content' => '',
            ),
            'pages/index',
            Page::INDEX_PAGE_SLUG
        );


    }
}
