<?php
/**
 * File: NewsletterSubscriberPivot.php
 * Created: 02-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\ORM;

class NewsletterSubscriberPivot extends ORM
{
    /**
     * @var string
     */
    public static $table = 'newsletter_subscriber_pivot';

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * Inserts the newsletter/subscriber pivot entries
     *
     * @param $newsletter
     * @param $sent_subscriber_ids
     * @return \PDOStatement
     */
    public static function insertFor($newsletter, $sent_subscriber_ids)
    {
        $params = array();
        foreach ($sent_subscriber_ids as $id) {
            $params[] = $newsletter->id;
            $params[] = $id;
        }

        $db = static::$db;
        $sql = "INSERT INTO " . static::$db->i(static::getTable())
            ."\n ({$db->i('newsletter_id')}, {$db->i('newsletter_subscriber_id')})"
            ."\n VALUES";

        for ($i = 0; $i < count($sent_subscriber_ids); ++$i) {
            $sql .= "\n(?, ?),";
        }
        $sql = rtrim($sql, ',');

        return static::$db->query($sql, $params);
    }
}
