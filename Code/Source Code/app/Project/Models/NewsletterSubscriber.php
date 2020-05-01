<?php
/**
 * File: NewsletterSubscriber.php
 * Created: 02-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Cipher;
use Story\Collection;
use Story\Error;
use Story\ORM;

/**
 * Class NewsletterSubscriber
 * @package Project\Models
 */
class NewsletterSubscriber extends ORM
{

    /**
     * @var string
     */
    public static $table = 'newsletter_subscriber';

    /**
     * @var string
     */
    protected static $foreign_key = 'newsletter_subscriber_id';

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {
        require_once SP . 'Project/Support/Events/newsletter_events.php';

        return parent::__construct($id);
    }

    /**
     * @param string $query
     * @param int $current
     * @param int $per_page
     *
     * @return array
     */
    public static function listSubscribersByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$table);
        $i = static::$db->i;

        $fields = array(
            "{$tbl}.{$i}email{$i}",
        );

        $queryWhere = query_to_where($query, $fields, '');

        return static::listSubscribers($current, $per_page, $queryWhere);
    }

    /**
     * @param $current
     * @param $per_page
     * @param null|array $queryWhere
     * @return array
     */
    public static function listSubscribers($current, $per_page, $queryWhere = null)
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

            $sql = "SELECT {$tbl}.*"
                . $sql_base
                . "\n ORDER BY {$db->i($tbl . '.email')} ASC"
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
     * Creates a subscriber from form
     *
     * @param array $input
     * @return bool
     */
    public static function createFromForm(array $input)
    {
        try {
            static::$db->pdo->beginTransaction();

            $model = new static;

            $model->set(array('email' => $input['email']));

            $model->save();

            static::$db->pdo->commit();

            return true;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }

    /**
     * Unsubscribe by model id
     * @param $id
     * @return bool|int
     */
    public static function unsubscribe($id)
    {
        // decode the id
        $id = static::decode($id);

        /** @var NewsletterSubscriber $model */
        $model = static::find($id);

        return static::unsubscribeByModel($model);
    }


    /**
     * Unsubscribe by email
     *
     * @param $email
     * @return bool
     */
    public static function unsubscribeByEmail($email)
    {
        $model = static::one(array('email' => $email));

        return static::unsubscribeByModel($model);

    }

    /**
     * Decodes the passed id
     * @param $id
     * @return string
     */
    public static function decode($id)
    {
        return Cipher::decrypt(base64_decode($id));
    }

    /*
     * Creates the encoded subscription id which can be used in urls
     */

    public static function encodeAsActionFromId($id, $action, array $parameters = array())
    {
        $model = new static;
        $model->set(array('id' => $id));

        return $model->encodeAsAction($action, $parameters);
    }

    /**
     * Unsubscribe by model
     *
     * @param NewsletterSubscriber $model
     * @return bool|int
     */
    public static function unsubscribeByModel(NewsletterSubscriber $model)
    {
        if (!$model) {
            return false;
        }

        event('newsletter.unsubscribed', $model);

        return $model->delete();
    }

    /**
     * Sends the unsubscribe email
     *
     * @param $email
     * @return bool
     */
    public static function sendUnsubscribeConfirmation($email)
    {
        $model = static::one(array('email' => $email));

        if ($model) {
            event('newsletter.unsubscribe_confirmation', $model);

            return true;
        }

        return false;
    }

    /**
     * Sends a confirmation email
     *
     * @param $email
     * @return mixed|null
     */
    public static function sendSubscribeConfirmation($email)
    {
        require_once SP . 'Project/Support/Events/newsletter_events.php';

        event('newsletter.subscribe_confirmation', $email);

        return true;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function subscribeByEmail($id)
    {
        $email = static::decode($id);

        if ($email) {
            $model = new static;
            $model->set(array('email' => $email));

            return $model->subscribe();
        }

        return false;
    }

    /**
     * Subscribe by model
     * @return $this
     */
    public function subscribe()
    {
        event('newsletter.subscribed', $this);

        return $this->save();
    }

    /**
     * Encodes the id of the subscriber
     *
     * @return string
     */
    public function encode()
    {
        return base64_encode(Cipher::encrypt($this->id));
    }

    /**
     * Encodes as an url
     *
     * @param $action
     * @param array $parameters
     * @return string
     */
    public function encodeAsAction($action, array $parameters = array())
    {
        return action($action, $parameters)
        . '?' . http_build_query(array('id' => $this->encode()));
    }

    /**
     * Updates the subscriber from form
     *
     * @param $input
     * @return bool
     */
    public function updateFromForm($input)
    {
        try {
            static::$db->pdo->beginTransaction();

            $this->set(array('email' => $input['email']));

            $this->save();

            static::$db->pdo->commit();

            return true;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }
}
