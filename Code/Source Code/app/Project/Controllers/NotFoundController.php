<?php
/**
 * File: NotFoundController.php
 * Created: 24-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers;

class NotFoundController extends BaseController
{
    public function run()
    {
        $this->show404();
    }
}
