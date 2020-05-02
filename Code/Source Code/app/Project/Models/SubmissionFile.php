<?php
/**
 * File: SubmissionFile.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

/**
 * Class SubmissionFile
 *
 * @property string storage_name
 * @package Project\Models
 */
class SubmissionFile extends ORM
{

    /**
     * Submission file assets directory
     */
    const ASSETS = 'storage/files/submission_assets/';

    /**
     * Submission files directory
     */
    const FILES = 'storage/files/submissions/';

    /**
     * Temporary submission file storage directory
     */
    const TEMP_FILES = 'storage/files/submissions/tmp/';
    /**
     * @var array
     */
    public static $belongs_to = array(
        'submission' => '\Project\Models\Submission',
    );

    /**
     * @var string
     */
    protected static $table = 'submission_files';

    /**
     * Gets the assets path for the filename
     *
     * @param $filename
     *
     * @return string
     */
    public static function getAssetsPath($filename)
    {
        return SP . static::ASSETS . $filename;
    }

    /**
     * Stores the submission file as a temp file
     *
     * @param $inputFile
     *
     * @return static
     */
    public static function storeTempFile($inputFile)
    {

        $model = new static;

        $file_name = random(32) . '_' . $inputFile['name'];
        $storage_name = static::getStorageName($file_name);
        $path = static::generateTempFilesPath($file_name);

        // create the directory if doesn't exists
        directory_is_writable(dirname($path));

        move_uploaded_file($inputFile['tmp_name'], $path);

        $model->set(
            array(
                'name'         => $inputFile['name'],
                'mime'         => get_mime($path),
                'storage_name' => $storage_name
            )
        );

        return $model;
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function getStorageName($name)
    {
        return mb_convert_case(mb_substr($name, 0, 2), MB_CASE_LOWER) . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * Generates the temp files path based on filename
     *
     * @param $filename
     *
     * @return string
     */
    public static function generateTempFilesPath($filename)
    {
        return SP . static::TEMP_FILES . mb_convert_case(mb_substr($filename, 0, 2), MB_CASE_LOWER)
        . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Returns all the models that belong to the user
     *
     * @param $user
     *
     * @return array
     */
    public static function allForUser(User $user)
    {
        // get the submission files
        $submission_ids = Submission::lists('id', null, array('user_id' => $user->id));

        // no submissions? nothing to do
        if (!count($submission_ids)) {
            return array();
        }

        // get the submission files
        $files = SubmissionFile::all(array('submission_id IN (' . implode(',', $submission_ids) . ')'));
        foreach ($files as $k => $file) {
            $files[$k] = new SubmissionFile($file);
        }

        return $files;
    }

    /**
     * Attempts to delete a submission file from the storage
     *
     * @return bool
     */
    public function deleteFile()
    {
        if (!$this->storage_name) {
            return false;
        }

        $path = static::getFilesPath($this->storage_name);

        return @unlink($path);
    }

    /**
     * Get the files path for the filename
     *
     * @param $filename
     *
     * @return string
     */
    public static function getFilesPath($filename)
    {
        return SP . static::FILES . $filename;
    }

    /**
     * Deletes the preview and preview assets for the submission file
     *
     * @return bool
     */
    public function deletePreview()
    {
        if (!$this->preview_key) {
            return false;
        }

        try {

            // if we have a preview key, we attempt to delete the preview and it's assets
            /** @var \Project\Services\Box\Preview $preview */
            $preview = app('container')->make('\Project\Services\PreviewInterface');

            $document = (object)array('id' => $this->preview_key);

            $preview->deleteDocument($document);

            $assetsDir = static::generateAssetsPath($this->preview_key);

            empty_dir($assetsDir, true);

            return true;
        } catch (\Exception $e) {
            log_message($e->getMessage());

            return false;
        }
    }

    /**
     *
     * Generates an assets path based on filename
     *
     * @param $filename
     *
     * @return string
     */
    public static function generateAssetsPath($filename)
    {
        return SP . static::ASSETS . mb_convert_case(mb_substr($filename, 0, 2), MB_CASE_LOWER)
        . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @return mixed
     */
    public function generatePreview()
    {
        $path = static::getFilesPath($this->storage_name);

        // upload the preview to box
        /** @var \Project\Services\Box\Preview $preview */
        $preview = app('container')->make('\Project\Services\PreviewInterface');

        $fileInfo = new \SplFileInfo($path);

        $result = $preview->uploadDocument($fileInfo);

        // get the key from box and store it
        return $result->id;
    }

    /**
     * Attempts to store the uploaded file into the submissions folder
     *
     * @param $inputFile
     *
     * @return string
     */
    public function storeFile($inputFile)
    {

        $file_name = random(32) . '_' . $inputFile['name'];
        $storage_name = static::getStorageName($file_name);
        $path = static::generateFilesPath($file_name);

        // create the directory if doesn't exists
        directory_is_writable(dirname($path));

        move_uploaded_file($inputFile['tmp_name'], $path);

        $this->name = $inputFile['name'];

        // set the mime of the file
        $this->mime = get_mime($path);

        // set the access key
        $this->access_key = random();

        return $this->storage_name = $storage_name;
    }

    /**
     * Generates a file path based on filename
     *
     * @param $filename
     *
     * @return string
     */
    public static function generateFilesPath($filename)
    {
        return SP . static::FILES . mb_convert_case(mb_substr($filename, 0, 2), MB_CASE_LOWER)
        . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Moves the temp file to a permanent location
     *
     * @return $this
     */
    public function moveTempFile()
    {

        $file_name = random(32) . '_' . $this->name;
        $storage_name = static::getStorageName($file_name);
        $path = static::generateFilesPath($file_name);

        // create the directory if doesn't exists
        directory_is_writable(dirname($path));

        $temp_file_path = static::getTempFilesPath($this->storage_name);

        if (@rename($temp_file_path, $path)) {
            $this->storage_name = $storage_name;
            // set the access key
            $this->access_key = random();
        }

        return $this;
    }

    /**
     * Gets the file path
     *
     * @param $id
     *
     * @return string
     */
    public static function getTempFilesPath($id)
    {
        return SP . static::TEMP_FILES . $id;
    }

    /**
     * Deletes the temp file if exists
     *
     * @return bool
     */
    public function deleteTempFile()
    {

        if (!$this->storage_name) {
            return false;
        }
        $path = static::getTempFilesPath($this->storage_name);

        if (!file_exists($path)) {
            return false;
        }

        return unlink($path);
    }
}
