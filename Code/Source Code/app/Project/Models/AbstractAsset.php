<?php
/**
 * File: AbstractAsset.php
 * Created: 11-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Story\Collection;
use Story\Error;
use Story\HTML;
use Story\ORM;
use Story\URL;

/**
 * Class AbstractAsset
 * @package Project\Models
 */
abstract class AbstractAsset extends ORM
{
    /**
     * Thumbnails folder
     */
    const THUMBNAILS = 'thumbnails';

    /**
     * Thumbnail height
     */
    const THUMBNAIL_HEIGHT = 300;

    /**
     * Thumbnail width
     */
    const THUMBNAIL_WIDTH = 300;

    /**
     * Uploads path
     */
    const UPLOADS = '';

    /**
     * @param string $query
     * @param int $current
     * @param int $per_page
     *
     * @return array
     */
    public static function listFilesByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$table);

        $i = static::$db->i;

        $fields = array(
            "{$tbl}.{$i}name{$i}",
            "{$tbl}.{$i}storage_name{$i}",
            "{$tbl}.{$i}meta{$i}",
            "{$tbl}.{$i}mime{$i}"
        );

        $queryWhere = query_to_where($query, $fields, '');

        return static::listFiles($current, $per_page, $queryWhere);
    }

    /**
     * @param $current
     * @param $per_page
     * @param null|array $queryWhere
     * @return array
     */
    public static function listFiles($current, $per_page, $queryWhere = null)
    {
        try {
            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$table);
            $where = array();
            $params = array();

            // check if we have query, and include that in the sql
            $query = '';
            if ($queryWhere) {

                $params = array_merge($params, $queryWhere['values']);

                if ($where) {
                    $query .= ' AND ';
                } else {
                    $query .= ' WHERE ';
                }

                $query .= "(" . $queryWhere['sql'] . ")";
            }

            $sql_base =
                // from
                "\n FROM {$tbl}"
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;

            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            // build the query
            $sql = "SELECT {$tbl}.* "
                // user data
                . $sql_base
                . "\n ORDER BY {$db->i($tbl . '.name')} ASC"
                . $sql_limit;

            // execute the query
            $items = static::$db->fetch($sql, $params);
            foreach ($items as $id => $row) {
                $items[$id] = new static($row);
            }
            $items = new Collection($items);

            // count sql
            $count_sql = "SELECT COUNT(DISTINCT {$db->i($tbl .'.id')})"
                . "\n" . $sql_base;

            $count = static::$db->column($count_sql, $params);

            // commit
            static::$db->pdo->commit();

            return array('total' => $count, 'items' => $items);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Returns the upload path
     *
     * @return string
     */
    public static function getUploadPath()
    {
        return PP . static::UPLOADS;
    }

    /**
     * Returns the thumbnail path
     *
     * @return string
     */
    public function getThumbnailPath()
    {
        return PP . static::THUMBNAILS . $this->storage_name;
    }

    /**
     * Deletes the file
     * @param null $id
     * @return int
     */
    public function delete($id = null)
    {
//        if ($id) {
//            $file = static::findOrFail($id);
//        }

        // we remove it from the filesystem
        $path = $this->status ? $this->getPath() : $this->getDisabledPath();

        // thumbnail
        $thumbnail_path = $this->getThumbnailPath();

        if (is_file($path)) {
            @unlink($path);
        }

        if (is_file($thumbnail_path)) {
            @unlink($path);
        }

        return parent::delete($id);
    }

    /**
     * Generates the preview or if no preview then an icon
     *
     * @param string $icon_prefix
     * @param array $attributes
     * @param string $icon_class
     * @return string
     */
    public function getPreview($icon_prefix = '', array $attributes = null, $icon_class = 'i--large')
    {
        $return = false;

        if ($this->canPreview()) {
            // generate the preview
            // based on the mime we generate the preview
            switch ($this->mime) {
                case 'image/png':
                case 'image/gif':
                case 'image/jpeg':
                    $return = $this->getImagePreview($attributes);
                    break;
            }
        }

        if ($return) {
            return $return;
        }

        return '<span class="' . $this->getFileTypeIcon($icon_prefix) . ' ' . $icon_class . '"></span>';
    }

    /**
     * Returns the preview path
     *
     * @return string
     */
    public function getPreviewPath()
    {
        return URL::to(static::THUMBNAILS . $this->storage_name);
    }
    /**
     * Returns the file size of the asset if file exists on the filesystem
     *
     * @return bool|int
     */
    public function getFileSize()
    {
        $file = static::getUploadPath() . $this->storage_name;

        // Check if file exists, we get the file name
        if (is_file($file)) {
            return filesize($file);
        }

        return false;
    }

    /**
     * Returns true if the asset can have a preview
     *
     * @return bool
     */
    public function canPreview()
    {
        // we can only preview the following mime types
        $preview_mime_types = array(
            'image/png',
            'image/gif',
            'image/jpeg'
        );

        if (in_array($this->mime, $preview_mime_types)) {

            return true;
        }

        return false;
    }

    /**
     * Returns the image preview
     *
     * @param array $attributes
     * @return string
     */
    public function getImagePreview(array $attributes = null)
    {
        $url = $this->getPreviewPath();

        return '<img src="' . $url . '"' . HTML::attributes($attributes) . '>';
    }

    /**
     * Returns the link to the file (even if it's disabled)
     * @param null|array $attributes
     * @return string
     */
    public function getLink(array $attributes = null)
    {
        $url = $this->url();

        return HTML::link($url, '/' . static::UPLOADS . $this->storage_name, $attributes);
    }

    /**
     * Returns the url to the file
     *
     * @return string
     */
    public function url()
    {
        return URL::to(static::UPLOADS . $this->storage_name);
    }

    /**
     * Returns the file path
     *
     * @return string
     */
    public function getPath()
    {
        return PP . static::UPLOADS . $this->storage_name;
    }

    /**
     * Changes the status of the file
     *
     * @param bool $status
     * @param bool $save
     * @return $this
     */
    public function setStatus($status = true, $save = false)
    {
        // If the status is the same as the requested status, we don't have to do anything
        if ((bool)$status == (bool)$this->status) {
            return $this;
        }

        $enabled_path = $this->getPath();
        $disabled_path = $this->getDisabledPath();

        // If status is true, we set it then save it
        if ($status) {
            $this->status = 1;
            // we attempt to rename the file, making them available
            if (file_exists($disabled_path)) {
                rename($disabled_path, $enabled_path);
            }

        } else {
            $this->status = 0;
            // we attempt to rename the file, making them unavailable
            if (file_exists($enabled_path)) {
                rename($enabled_path, $disabled_path);
            }

        }

        if ($save) {
            $this->save();
        }

        return $this;
    }

    /**
     * Returns the file type icon
     * @param string $prefix
     * @return string
     */
    public function getFileTypeIcon($prefix = '')
    {

        switch ($this->mime) {
            case 0 === strpos($this->mime, 'image/'):
                return $prefix . 'file-photo-o';
                break;
            case 0 === strpos($this->mime, 'text/'):
                return $prefix . 'file-text-o';
                break;
            case 0 === strpos($this->mime, 'application/pdf'):
                return $prefix . 'file-pdf-o';
                break;
            case 0 === strpos($this->mime, 'application/vnd.ms-powerpoint'):
            case 0 === strpos($this->mime, 'application/vnd.openxmlformats-officedocument.presentationml.presentation'):
                return $prefix . 'file-powerpoint-o';
                break;
            case 0 === strpos($this->mime, 'application/msword'):
            case 0 === strpos($this->mime, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'):
                return $prefix . 'file-word-o';
                break;
            case 0 === strpos($this->mime, 'application/vnd.ms-excel'):
            case 0 === strpos($this->mime, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'):
                return $prefix . 'file-excel-o';
                break;
            default:
                return $prefix . 'file-o';
                break;
        }
    }

    /**
     * Created accessor
     *
     * @param $value
     * @return static
     */
    public function getCreatedAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Modified accessor
     *
     * @param $value
     * @return static
     */
    public function getModifiedAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Returns the disabled path
     *
     * @return string
     */
    public function getDisabledPath()
    {
        $enabled_path = $this->getPath();

        return dirname($enabled_path) . DIRECTORY_SEPARATOR . '.' . basename($enabled_path);
    }
}
