<?php
/**
 * File: Export.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Project\Services\Exporter\ExporterFactoryInterface;
use Story\Collection;
use Story\Error;
use Story\ORM;

/**
 * Class Export
 * @package Project\Models
 */
class Export extends ORM
{
    /**
     * @var string
     */
    protected static $table = 'exports';

    /**
     * @param string      $query
     * @param int         $current
     * @param int         $per_page
     *
     * @return array
     */
    public static function listExportsByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$table);
        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}name{$i}", "{$tbl}.{$i}description{$i}", "{$tbl}.{$i}exporter{$i}");

        $queryWhere = query_to_where($query, $fields, '');

        return static::listExports($current, $per_page, $queryWhere);
    }

    /**
     * @param             $current
     * @param             $per_page
     * @param null|array  $queryWhere
     *
     * @return array
     */
    public static function listExports($current, $per_page, $queryWhere = null)
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
     * Creates the export from form
     *
     * @param                          $data
     *
     * @param ExporterFactoryInterface $exporter
     *
     * @return static
     */
    public static function createFromForm($data, ExporterFactoryInterface $exporter)
    {
        $model = new static;

        $model->set(
            array(
                'name'        => $data['name'],
                'status'      => isset($data['status']) ? (int)$data['status'] : null,
                'description' => $data['description'],
                'exporter'    => get_class($exporter->get($data['exporter'])),
                'columns'     => isset($data['columns']) ? $data['columns'] : array()
            )
        );

        return $model->save();
    }

    /**
     * @param $data
     * @param $exporter
     *
     * @return $this
     */
    public function updateFromForm($data, ExporterFactoryInterface $exporter)
    {
        $this->set(
            array(
                'name'        => $data['name'],
                'status'      => isset($data['status']) ? (int)$data['status'] : null,
                'description' => $data['description'],
                'exporter'    => get_class($exporter->get($data['exporter'])),
                'columns'     => isset($data['columns']) ? $data['columns'] : array()
            )
        );

        return $this->save();
    }

    /**
     * Cols attribute accessor
     *
     * @param $value
     *
     * @return mixed
     */
    public function getColumnsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Cols attribute mutator
     *
     * @param $value
     *
     * @return string
     */
    public function setColumnsAttribute($value)
    {
        return $this->attributes['columns'] = json_encode($value);
    }

    /**
     * Created accessor
     *
     * @param $value
     *
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
     *
     * @return static
     */
    public function getModifiedAttribute($value)
    {
        return Carbon::createFromTimestamp($value);
    }

    /**
     * Returns a filename friendly export name with timestamp attached to it
     *
     * @param Carbon $date
     *
     * @return string
     */
    public function buildNameWithTimestamp(Carbon $date = null)
    {
        if (!$date) {
            $date = Carbon::now();
        }

        return slug($this->name, '_') . '_' . $date->format('Y-m-d-H-i');
    }
}
