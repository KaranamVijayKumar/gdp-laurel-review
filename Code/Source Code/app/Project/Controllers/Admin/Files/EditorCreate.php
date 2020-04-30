<?php
/**
 * File: EditorCreate.php
 * Created: 01-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Files;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\PublicAsset;

/**
 * Class EditorCreate
 * @package Project\Controllers\Admin\Files
 */
class EditorCreate extends AdminBaseController
{
    /**
     * Uploads a file
     */
    public function post()
    {
        // We are using custom callbacks to return the proper json responses for the redactor editor
        $this->json = PublicAsset::storeUploadedFile(
            // success handler
            function ($model) {
                return array(
                    'filelink' => $model->url(),
                    'filename' => $model->name
                );
            },
            // error handler
            function ($errors) {
                $errorMsg = '';
                foreach ((array) $errors as $error) {
                    $error = array_unique((array)$error);
                    $errorMsg .= current($error);
                }

                return array(
                    'error' => true,
                    'message' => $errorMsg
                );
            }
        );

    }
}
