<?php
/**
 * File: PageController.php
 * Created: 30-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

use Story\Error;

/**
 * Class PageController
 *
 * @package Project\Controllers
 */
class PageController extends AbstractPage
{
    /**
     * Shows a page
     */
    public function get()
    {

        try {

            $name = trim(html2text(implode('/', func_get_args())));
            $this->buildPage($name);

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
