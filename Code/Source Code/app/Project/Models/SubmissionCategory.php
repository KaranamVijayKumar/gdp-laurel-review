<?php
/**
 * File: SubmissionCategory.php
 * Created: 26-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\Error;
use Story\ORM;

class SubmissionCategory extends ORM
{

    /**
     * @var array
     */
    public static $has_many = array(
        'submissions' => 'Project\Models\Submissions',
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'submission_category_id';

    /**
     * @var string
     */
    protected static $table = 'submission_categories';

    /**
     * Name of the all categories
     */
    const ALL = 'all';

    public static function listCategories($current, $per_page)
    {

        try {


            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current ? $current - 1 : 1);

            $tbl = static::$db->i(static::$table);
            $sql = "SELECT * FROM $tbl ORDER BY " . static::$db->i('name') . " ASC " .
                "LIMIT $per_page OFFSET $offset";
            $items = new Collection(static::$db->fetch($sql));

            // get count
            $count = static::$db->select('COUNT(*)', static::$table);
            $count = static::$db->column($count[0], $count[1]);

            static::$db->pdo->commit();

            static::countAllSubmissions($items);

            return array('total' => $count, 'items' => $items);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Counts all the submissions for a category
     *
     * @param Collection $items
     */
    private static function countAllSubmissions(Collection $items)
    {

        if (!count($items)) {
            return;
        }

        $ids = $items->lists();
        $idPlaceholders = trim(str_repeat('?,', count($ids)), ',');
        $i = static::$db->i;

        $sql = ("SELECT {$i}submission_category_id{$i}, COUNT(*) as {$i}submissionCount{$i} " .
            "FROM {$i}submissions{$i} WHERE {$i}submission_category_id{$i} IN ({$idPlaceholders}) " .
            "GROUP BY {$i}submission_category_id{$i}");

        $submissionCounts = new Collection(static::$db->fetch($sql, $ids));


        foreach ($submissionCounts as $submissionCount) {
            $item = $items->findBy('id', $submissionCount->submission_category_id);
            if (!isset($item->submissionCount)) {
                $item->submissionCount = 0;
            }
            $item->submissionCount = $submissionCount->submissionCount;
        }

    }

    public static function listCategoriesByQuery($query, $current, $per_page)
    {

        try {

            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current ? $current - 1 : 1);

            $tbl = static::$db->i(static::$table);

            $where = query_to_where(
                $query,
                array('name', 'guidelines_text', 'size_limit', 'amount')
            );


            $sql = "SELECT * FROM $tbl WHERE {$where['sql']} ORDER BY " . static::$db->i('name') . " ASC " .
                "LIMIT $per_page OFFSET $offset";
            $items = new Collection(static::$db->fetch($sql, $where['values']));

            // get count
            $sql = "SELECT COUNT(*) as c FROM {$tbl} WHERE {$where['sql']}";
            /** @var \stdClass $count */
            $count = static::$db->row($sql, $where['values']);
            $count = $count->c;


            static::$db->pdo->commit();

            static::countAllSubmissions($items);

            return array('total' => $count, 'items' => $items);

        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }
}
