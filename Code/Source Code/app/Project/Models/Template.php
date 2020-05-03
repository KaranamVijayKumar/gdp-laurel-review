<?php
/**
 * File: Templates.php
 * Created: 15-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Intervention\Image\Exception\NotFoundException;
use Story\Auth;
use Story\Collection;
use Story\Error;
use Story\ORM;
use StoryEngine\StoryEngine;

/**
 * Class Template
 * @package Project\Models
 */
class Template extends ORM
{

    /**
     * Prefix for the locked template derivates
     *
     * @var string
     */
    public static $derivate_prefix = '__';

    /**
     * @var string
     */
    protected static $table = 'templates';

    /**
     * Clonable templates. Update as needed
     *
     * @var array
     */
    public static $clonables = array(
        'submission' => array(SubmissionStatus::STATUS_ACCEPTED, SubmissionStatus::STATUS_DECLINED),
        'newsletter' => array(Newsletter::TEMPLATE_NAME),
    );

    /**
     * Returns the clonable templates
     *
     * @return array
     */
    public static function getClonables()
    {
        uksort(static::$clonables, 'strcasecmp');

        // build the where sql
        $sql = array();
        $params = array();
        foreach (static::$clonables as $type => $clonable) {
            $sql[] = '(' . static::$db->i('type') . ' = ? AND '
                . static::$db->i('name') . ' IN (' . implode(',', array_fill(0, count($clonable), '?')) . '))';
            $params[] = $type;
            $params = array_merge($params, $clonable);
        }

        $db = static::$db;
        $query = "SELECT * FROM {$db->i(static::getTable())} WHERE {$db->i('locked')} = 1 AND "
            . implode(' OR ', $sql)
            . ' ORDER BY ' . $db->i('type') . ', ' . $db->i('subject') . ' ASC';

        $items = $db->fetch($query, $params);

        if ($items) {
            foreach ($items as $id => $row) {
                $items[$id] = new static($row);
            }
        }
        $items = new Collection($items);

        if (count($items)) {
            $return = array();
            foreach ($items as $template) {
                $return[$template->id] = ucwords($template->type) . ': ' . $template->description;
            }

            return $return;

        } else {
            return array();
        }
    }

    /**
     * Returns all the entries with also the custom created ones
     *
     * @param null $where
     * @param int $limit
     * @param int $offset
     * @return Collection
     *
     */
    public static function allWithCustom($where = null, $limit = 0, $offset = 0)
    {
        $tbl = static::$db->i(static::$table);
        $sql = "SELECT * FROM {$tbl}";

        // Process WHERE conditions
        list($where_sql, $params) = static::$db->where($where);

        if ($where_sql) {
            $sql .= " WHERE ($where_sql)";
        }

        // do we have name and type in the where? If yes, we add a like condition also
        $extra_where = '';
        if (isset($where['name']) && isset($where['type'])) {
            $extra_where = ' OR (' . static::$db->i('name') . ' LIKE ? AND ' . static::$db->i('type') . ' = ?)';
            $params[] = $where['name'] . static::$derivate_prefix . '%';
            $params[] = $where['type'];
        }

        if ($extra_where) {
            $sql .= $extra_where;
        }

        if ($limit) {
            // MySQL/PgSQL use a different LIMIT syntax
            $sql .= static::$db->type == 'pgsql' ? " LIMIT $limit OFFSET $offset" : " LIMIT $offset, $limit";
        }

        $rows = static::$db->fetch($sql, $params);

        foreach ($rows as $id => $row) {
            $rows[$id] = new static($row);
        }

        return new Collection($rows);
    }

    /**
     * Lists the templates by name filtering them by the query
     *
     * @param string $query
     * @param int $current Current page
     * @param int $per_page Items per page
     *
     * @return array
     */
    public static function listByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$table);
        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}type{$i}", "{$tbl}.{$i}name{$i}", "{$tbl}.{$i}description{$i}",);
        $where = query_to_where($query, $fields, '');

        return static::listByName($current, $per_page, $where);
    }

    /**
     * Lists the templates by their name name
     *
     * @param int $current Current page
     * @param int $per_page Items per page
     *
     * @param null $queryWhere
     * @return array
     */
    public static function listByName($current, $per_page, $queryWhere = null)
    {

        try {

            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $where = array();
            $params = array();

            // do we have $queryWhere? if so, we merge the parameters
            if ($queryWhere) {
                $params = array_merge($params, $queryWhere['values']);
            }

            // check if we have query, and include that in the sql
            $query = '';
            if ($queryWhere) {

                if ($where) {
                    $query .= ' AND ';
                } else {
                    $query .= 'WHERE ';
                }

                $query .= "(" . $queryWhere['sql'] . ")";
            }


            $i = static::$db->i;
            $tbl = $i . static::$table . $i;

            $select = array();

            foreach (array('id', 'locked', 'type', 'name', 'description',) as $s) {
                $select[] = $i . $s . $i;
            }

            $select = implode(',', $select);

            $sql = "SELECT {$select}  FROM " . $tbl .
                (count($where) ? "WHERE " . implode(" AND ", $where) : '') . "\n" .
                // add the search query
                $query . "\n" .
                // order by submissison created asc
                "ORDER BY {$i}type{$i}, {$i}name{$i} ASC \n" .
                // limit
                "LIMIT $per_page OFFSET $offset";

            $items = new Collection(static::$db->fetch($sql, $params));

            $sql = "SELECT COUNT(*) FROM $tbl \n" .
                (count($where) ? "WHERE " . implode(" AND ", $where) : '') . "\n" .
                // add the search query
                $query . "\n";

            $count = static::$db->column($sql, $params);

            static::$db->pdo->commit();

            return array('total' => $count, 'items' => $items,);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection(),);
    }

    /**
     * Returns the template list, suitable for select html elements
     *
     * @param array $where
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getList(array $where = null, $limit = 0, $offset = 0)
    {
        $order = array('type' . static::$db->i . ', ' . static::$db->i . 'name' => 'asc');
        $templates = new Collection(static::fetch($where, $limit, $offset, $order));


        if (count($templates)) {
            $templates->load();
            $return = array();
            foreach ($templates as $template) {
                $return[$template->id] = ucwords($template->type) . ': ' . $template->description;
            }

            return $return;

        } else {
            return array();
        }

    }

    /**
     * Creates a template from admin user input
     *
     * @param $data
     * @return mixed
     */
    public static function createFromForm($data)
    {
        try {

            /** @var Template $default_template */
            $default_template = static::one(array('id' => $data['default_template'], 'locked' => '1'));

            if (!$default_template) {
                throw new NotFoundException('Invalid default template selected.');
            }

            $model = new static;

            $model->set(
                array(
                    'locked'      => null,
                    'type'        => $default_template->type,
                    'name'        => $default_template->name . static::$derivate_prefix . random(7),
                    'subject'     => $data['subject'],
                    'message'     => $data['message'],
                    'variables'   => $default_template->attributes['variables'],
                    'description' => $data['description'],
                    'created'     => time()
                )
            );


            if ($saved = $model->save(true)) {

                // Log who created this template
                Log::create(
                    $model,
                    'Template created by {user}.',
                    array(
                        'user'          => Auth::user()->id,
                        'user_fallback' => Auth::user()->profiles->load()->findBy('name', 'name')->value,
                    )
                );

                return $saved;
            }

            return false;

        } catch (\Exception $e) {
            Error::exception($e);
        }

        return false;
    }

    /**
     * Updates the template. Also updates the description if not locked
     *
     * @param $input
     * @return $this
     */
    public function updateFromForm($input)
    {
        $this->set(
            array(
                'subject' => $input['subject'],
                'message' => $input['message'],
            )
        );

        if (!$this->locked) {
            $this->description = $input['description'];
        }

        return $this->save();
    }

    /**
     * Returns the variables attribute
     *
     * @param $value
     *
     * @return mixed
     */
    public function getVariablesAttribute($value)
    {

        return $value ? json_decode($value, true) : $value;
    }

    /**
     * Delete the current object (and all related objects) from the database
     *
     * @param int $id to delete
     *
     * @return int
     */
    public function delete($id = null)
    {
        if ($this->locked && $id === null) {
            return false;
        }

        return parent::delete($id);
    }

    /**
     * Message attribute accessor. Also executes the php echo statements like {{ time() }}
     * @param $value
     * @return mixed
     */
    public function getMessageAttribute($value)
    {
        /** @var StoryEngine $engine */
        $engine = app('storyengine');
        $parser = $engine->getParser();
        $parser->execute($value);

        return $value;
    }
}
