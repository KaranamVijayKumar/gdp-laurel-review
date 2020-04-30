<?php
/**
 * File: EditorIndex.php
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
 * Class EditorIndex
 * @package Project\Controllers\Admin\Files
 */
class EditorIndex extends AdminBaseController
{
    /**
     * Returns a file list
     */
    public function get($mode = 'files')
    {


        if ($mode == 'images') {

            $this->json = PublicAsset::getFileList(1000, $mode, function ($items) {

                $files = array();
                /** @var PublicAsset $item */
                foreach ($items['items'] as $item) {
                    $files[] = array(
                        'title' => pathinfo($item->name, PATHINFO_FILENAME),
                        'thumb'  => $item->getPreviewPath(),
                        'image'  => $item->url()
                    );
                }

                return $files;
            });

        } else {
            $this->json = PublicAsset::getFileList(1000, 'files', function ($items) {

                $files = array();
                /** @var PublicAsset $item */
                foreach ($items['items'] as $item) {
                    $files[] = array(
                        'title' => pathinfo($item->name, PATHINFO_FILENAME),
                        'name'  => $item->name,
                        'link'  => $item->url(),
                        'size'  => get_file_size($item->getFileSize())
                    );
                }

                return $files;
            });
        }


        if (!count($this->json)) {
            $this->json = '{}';
        }
    }
}
