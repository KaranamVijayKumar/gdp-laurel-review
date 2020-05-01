<?php
/**
 * File: PublicAsset.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;
use Story\Error;
use Story\ORM;
use Story\Validator;

/**
 * Class PublicAsset
 *
 * @package Project\Models
 */
class PublicAsset extends AbstractAsset
{

    /**
     * Thumbnails folder
     */
    const THUMBNAILS = 'uploads/files/thumbnails/';

    /**
     * Uploads path
     */
    const UPLOADS = 'uploads/files/';

    /**
     * @var string
     */
    protected static $table = 'public_assets';


    /**
     * Stores the uploaded file
     *
     * @param null|callable $success_callback
     * @param null|callable $error_callback
     * @return array
     */
    public static function storeUploadedFile($success_callback = null, $error_callback = null)
    {
        try {

            static::sendHeaders();
            // validate if the user uploaded a file

            $v = new Validator(array_merge($_POST, $_FILES));

            $v->rule('upload', 'file');
            $v->rule('required', 'file');

            // we need to return the file name as result or the error message:
            // if the file was validated we attempt to store the file
            if ($v->validate() && ($model = static::storeFile($_FILES['file'])) instanceof static) {

                // check if we can generate a preview
                $model->generatePreview();

                // save
                $model->save();

                if (!$success_callback) {
                    // save the notice & return the result
                    /** @var \Story\Session $session */
                    $session = app('session');
                    $upload_count = $session->get('fileupload_count', 0);
                    $upload_count++;
                    $session->put('fileupload_count', $upload_count);
                    $session->flash('notice', sprintf('Uploaded %s files.', $upload_count));

                    return array(
                        'result' => $model->name
                    );
                } else {
                    return call_user_func($success_callback, $model);
                }
            }

            if (!$error_callback) {
                $errorMsg = '';
                foreach ($v->errors() as $error) {
                    $error = array_unique($error);
                    $errorMsg .= current($error);
                }

                return array(
                    'error' => $errorMsg
                );
            } else {
                return call_user_func($error_callback, $v->errors());
            }

        } catch (\Exception $e) {
            Error::exception($e);
            if (!$error_callback) {
                return array(
                    'error' => 'Error processing the file.'
                );
            } else {
                return call_user_func($error_callback, array('error' => 'Error processing the file.'));
            }

        }

    }

    /**
     * Sends the appropriate headers for the response
     */
    public static function sendHeaders()
    {

        // Send the appropriate headers for the response
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
        ) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }

    }

    /**
     * Returns the file list
     *
     * @param int $limit
     * @param string $only
     * @param null|callable $generator
     * @return array|mixed
     */
    public static function getFileList($limit = 1000, $only = 'files', $generator = null)
    {

        $query = null;

        $image_mimes = array(
            'image/png',
            'image/gif',
            'image/jpeg',
        );

        $query = array(
            'sql'    => static::$db->i('mime') . ($only != 'images' ? ' NOT ' : '')
                . ' IN (' . trim(str_repeat('?,', count($image_mimes)), ',') . ')',
            'values' => $image_mimes
        );

        // show only the enabled assets
        $query['sql'] .= 'AND ' . static::$db->i('status') . ' = 1';

        // we have query, get the assets filtered
        $items = PublicAsset::listFiles(
            1,
            $limit,
            $query
        );

        if (is_callable($generator)) {
            return call_user_func($generator, $items);
        } else {
            return $items;
        }

    }

    /**
     * Saves the file into the filesystem
     *
     * @param $file
     * @return bool|static
     */
    private static function storeFile($file)
    {
        $prefix = random(10);
        $folder = mb_strtolower(substr($prefix, 0, 2));
        $path = static::getUploadPath() . $folder . DIRECTORY_SEPARATOR;

        // create the directory
        if (!is_dir($path) && directory_is_writable($path)) {

            $storage_name = $prefix . '_' . sanitize_filename($file['name']);
            $filePath = $path . $storage_name;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $model = new static;
                $model->set(
                    array(
                        'name'         => $file['name'],
                        'storage_name' => $folder . DIRECTORY_SEPARATOR . $storage_name,
                        'mime'         => get_mime($filePath),
                        'status'       => '1'
                    )
                );

                return $model;
            }

        }

        return false;
    }

    /**
     * Generates the image preview if possible
     *
     * @return bool
     */
    public function generatePreview()
    {
        $preview_mime_types = array(
            'image/png',
            'image/gif',
            'image/jpeg'
        );
        // we generate only for png, jpeg and gif previews
        if (!in_array($this->mime, $preview_mime_types)) {
            return false;
        }

        $path = static::getUploadPath() . $this->storage_name;
        $thumbnailPath = $this->getThumbnailPath();

        if (directory_is_writable(dirname($thumbnailPath))) {

            // resize
            $manager = new ImageManager(array('driver' => extension_loaded('imagick') ? 'imagick' : 'gd'));
            $image = $manager->make($path);
            $image->resize(
                static::THUMBNAIL_WIDTH,
                static::THUMBNAIL_HEIGHT,
                function ($constraint) {

                    /** @var Constraint $constraint */
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );
            if ($this->mime === 'image/jpeg') {
                $image->orientate();
            }
            $image->sharpen(5);
            $image->save($thumbnailPath);
            return true;
        }
        return false;
    }
}
