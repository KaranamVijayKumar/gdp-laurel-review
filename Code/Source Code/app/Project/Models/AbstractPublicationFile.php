<?php
/**
 * File: AbstractPublicationFile.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Project\Interfaces\PublicationFile;
use Story\ORM;
use Intervention\Image\Constraint;
use Intervention\Image\ImageManager;
use Story\URL;

/**
 * Class AbstractPublicationFile
 * @package Project\Models
 */
abstract class AbstractPublicationFile extends ORM implements PublicationFile
{

    /**
     * Cover height
     */
    const COVER_HEIGHT = 600;
    /**
     * Cover width
     */
    const COVER_WIDTH = 400;
    /**
     * Relative storage path
     *
     */
    const RELATIVE_STORAGE_PATH = 'uploads/publications';

    /**
     * @var string
     */
    public static $blurred_image_prefix = 'blurred_';

    /**
     * Allowed cover page file types
     *
     * @var array
     */
    public static $coverPageFileTypes = array('jpeg', 'png', 'gif');
    /**
     * Create blurred version of the cover or not
     *
     * @var bool
     */
    public static $create_blurred = true;

    /**
     * Generates the blurred cover page image url
     *
     * @param $storage_name
     *
     * @return string
     */
    public static function createBlurredCoverPageImageUrl($storage_name)
    {
        return URL::to(static::RELATIVE_STORAGE_PATH . '/' . static::$blurred_image_prefix . $storage_name);
    }

    /**
     * Updates the cover image for the publication
     *
     * @param AbstractPublication $publication
     * @param                     $data
     *
     * @return mixed
     */
    public static function updatePublicationCoverImageFromForm(AbstractPublication $publication, $data)
    {
        // we check if a file was uploaded
        // if no file was uploaded we have nothing to do
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
            return $publication->cover_image;
        }

        // user uploaded a file:

        // delete the existing one
        if ($publication->cover_image) {
            $publication->cover_image->delete();
        }


        // create the new one
        return $publication->cover_image = static::createPublicationCoverImageFromForm($publication, $data);
    }

    /**
     * Creates the file for the publication from user input (form)
     *
     * @param AbstractPublication $publication
     * @param array               $data
     * @param string              $key
     *
     * @return mixed
     */
    public static function createPublicationCoverImageFromForm(
        AbstractPublication $publication,
        array $data,
        $key = 'file'
    ) {
        $model = new static;

        $model->set(
            array(
                $publication::TYPE . 'able_id'   => $publication->id,
                $publication::TYPE . 'able_type' => get_class($publication),
                'name'                           => $data[$key]['name']
            )
        );

        $uploaded_file = $_FILES[$key];

        // resize store the file

        $path = static::getStorageFileName($uploaded_file);
        // create the resized cover page image
        static::resizeAndSaveUploadedPublicationCoverImage($uploaded_file, $path);

        if (static::$create_blurred) {
            // create a blurred version also
            static::createBlurredCoverImageFromPath($path);
        }


        $model->set(
            array(
                'name'         => $uploaded_file['name'],
                'mime'         => get_mime($path),
                'storage_name' => basename($path)
            )
        );

        return $model->save();
    }

    /**
     * Returns the storage file name
     *
     * @param $uploaded_file
     *
     * @return string
     */
    protected static function getStorageFileName($uploaded_file)
    {

        $extension = get_file_extension($uploaded_file['tmp_name']);
        $filename = pathinfo($uploaded_file['name'], PATHINFO_FILENAME);

        $path = static::getCoverStoragePath() . random() .
            '_' . slug($filename) . ($extension ? '.' . $extension : '');

        return $path;
    }

    /**
     * Returns the storage path
     *
     * @return string
     */
    public static function getCoverStoragePath()
    {
        return PP . static::RELATIVE_STORAGE_PATH . '/';
    }

    /**
     * Resize and saves the uploaded publication cover image
     *
     * @param $uploaded_file
     * @param $path
     */
    protected static function resizeAndSaveUploadedPublicationCoverImage($uploaded_file, $path)
    {

        // resize
        $manager = new ImageManager(array('driver' => extension_loaded('imagick') ? 'imagick' : 'gd'));
        $image = $manager->make($uploaded_file['tmp_name']);
        $image->fit(
            static::COVER_WIDTH,
            static::COVER_HEIGHT,
            function ($constraint) {

                /** @var Constraint $constraint */
                $constraint->aspectRatio();
            }
        );
        $image->orientate();
        $image->sharpen(5);
        $image->save($path);

        @unlink($uploaded_file['tmp_name']);
    }

    /**
     * Create a blurred version of the already processed cover image
     *
     * @param $path
     */
    protected static function createBlurredCoverImageFromPath($path)
    {
        $manager = new ImageManager(array('driver' => extension_loaded('imagick') ? 'imagick' : 'gd'));
        $image = $manager->make($path);
        $image->blur(10);
        $image->save(dirname($path) . DIRECTORY_SEPARATOR . static::$blurred_image_prefix . basename($path));
    }

    /**
     * Returns the cover page image url
     *
     * @return string
     */
    public function getCoverPageImageUrl()
    {
        return static::createCoverPageImageUrl($this->storage_name);
    }

    /**
     * Generates the cover page image url
     *
     * @param $storage_name
     *
     * @return string
     */
    public static function createCoverPageImageUrl($storage_name)
    {

        return URL::to(static::RELATIVE_STORAGE_PATH . '/' . $storage_name);
    }

    /**
     * Returns the storage name
     *
     * @return string
     */
    public function getStorageName()
    {
        return $this->storage_name;
    }

    /**
     * Removes the model and the file associated with it
     *
     * @param null $id
     *
     * @return int
     */
    public function delete($id = null)
    {
        $model = $id ? $this->find($id) : $this;

        if ($model) {
            // remove the file from the filesystem
            $file_path = static::getCoverStoragePath() . $this->storage_name;
            @unlink($file_path);
            // delete the blurred version also
            $blurred_file_path = static::getCoverStoragePath() . static::$blurred_image_prefix . $this->storage_name;
            @unlink($blurred_file_path);
        }

        return parent::delete($id);
    }
}
