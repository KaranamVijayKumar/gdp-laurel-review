<?php
/**
 * File: Newsletter.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\Error;
use Story\ORM;

/**
 * Class Newsletter
 *
 * @package Project\Models
 */
class Newsletter extends ORM
{
    /**
     * Default template name
     */
    const TEMPLATE_NAME = 'default';

    /**
     * @var array
     */
    public static $has_many = array(
        'content' => 'Project\Models\NewsletterContent',
    );

    /**
     * @var string
     */
    protected static $table = 'newsletters';

    /**
     * @var string
     */
    protected static $foreign_key = 'newsletter_id';

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {
        require_once SP .'Project/Support/Events/newsletter_events.php';

        return parent::__construct($id);
    }

    /**
     * Returns the templates
     *
     * @return mixed
     */
    public static function getTemplates()
    {
        $templates = Template::allWithCustom(
            array(
                'name' => static::TEMPLATE_NAME,
                'type' => 'newsletter'
            )
        );

        return $templates->lists('id', 'description');
    }

    /**
     * Create from news article
     *
     * @param $article
     * @param $input
     * @return static
     */
    public static function createFromCreatedNews($article, $input)
    {
        // get the email template for the newsletter
        $dbTemplate = self::getDbTemplate();

        $subject = $input['title'];

        $content = isset($input['required-section-headline']) ? $input['required-section-headline'] : '';
        $content .= "\n" . $input['required-section-content'];

        // create the newsletter
        $newsletter = new static;
        $newsletter->set(
            array(
                'template_id' => $dbTemplate->id,
                'status'      => '1',
                'sent'        => null,
                'notes'       => 'Created from news article #' . $article->id,
                'created'     => time(),
            )
        );
        $newsletter->save();

        // create the newsletter_content.subject & newsletter_content.content from headline and content
        NewsletterContent::createContent($newsletter, $subject, $content);

        event('newsletter.created', $newsletter);

        // return the new newsletter
        return $newsletter;
    }

    /**
     * @param string $query
     * @param int $current
     * @param int $per_page
     *
     * @return array
     */
    public static function listNewslettersByQuery($query, $current, $per_page)
    {

        $tbl = static::$db->i(static::$table);
        $tlb_content = NewsletterContent::getTable();
        $i = static::$db->i;

        $fields = array(
            "{$tbl}.{$i}notes{$i}",
            static::$db->i($tlb_content . '.subject'),
            static::$db->i($tlb_content . '.content_text'),
        );

        $queryWhere = query_to_where($query, $fields, '');

        return static::listNewsletters($current, $per_page, $queryWhere);
    }

    /**
     * @param $current
     * @param $per_page
     * @param null|array $queryWhere
     * @return array
     */
    public static function listNewsletters($current, $per_page, $queryWhere = null)
    {
        try {
            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $db = static::$db;
            $tbl = static::$db->i(static::$table);
            $tlb_content = NewsletterContent::getTable();
            $i = static::$db->i;

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
                // join
                . "\n LEFT JOIN {$tlb_content} ON {$tbl}.{$i}id{$i} = {$tlb_content}.{$i}newsletter_id{$i} "
                // where
                . (count($where) ? "\n WHERE " . implode(" AND ", $where) : '')
                // add the search query
                . "\n " . $query;

            // select query
            $sql_limit = "\n LIMIT $per_page OFFSET $offset";

            // build the query
            $sql = "SELECT {$tbl}.*," .
                "{$tlb_content}.{$i}subject{$i}, {$tlb_content}.{$i}content{$i}, {$tlb_content}.{$i}content_text{$i}"
                // user data
                . $sql_base
                . "\n ORDER BY {$db->i($tbl . '.created')} DESC"
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
     * Creates from admin form
     *
     * @param $input
     * @return bool
     */
    public static function createFromForm($input)
    {
        try {
            static::$db->pdo->beginTransaction();
            $model = new static;

            $model->set(
                array(
                    'template_id' => $input['template'],
                    'status'      => isset($input['status']) ? (int)$input['status'] : 0,
                    'sent'        => null,
                    'notes'       => $input['notes'],
                )
            );

            $model->save();

            // create the content
            NewsletterContent::createContent($model, $input['subject'], $input['content']);

            static::$db->pdo->commit();
            event('newsletter.created', $model);

            return true;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }

    /**
     * Returns the email template for the newsletter
     *
     * @return null|Template
     */
    protected static function getDbTemplate()
    {

        $template = Template::one(
            array('type' => 'newsletter', 'name' => static::TEMPLATE_NAME,)
        );

        if (!$template) {
            $template = new Template;
            $template->set(array('subject' => '', 'content' => '',));
        }

        return $template;
    }

    /**
     * Returns the email addresses which this newsletter was not sent
     *
     * @return array
     */
    public function unsentToSubscribers()
    {
        // select from subscribers where newsletter_id != $this->id
        $db = static::$db;
        $tbl_subscriber = $db->i(NewsletterSubscriber::getTable());
        $tbl_pivot = $db->i(NewsletterSubscriberPivot::getTable());

        // $i = static::$db->i;

        /*
         * select id, email
        from newsletter_subscriber
        where id not in (
        select newsletter_subscriber_id as id from newsletter_subscriber_pivot where newsletter_id = 1
        )
         */

        $subquery = "SELECT {$db->i('newsletter_subscriber_id')} as id"
            . "\n\t\t    FROM {$tbl_pivot}"
            . "\n\t\t    WHERE {$db->i('newsletter_id')} = ?";

        $sql = "SELECT {$db->i('id')}, {$db->i('email')}"
            . "\n FROM {$tbl_subscriber}"
            . "\n WHERE {$db->i('id')} NOT IN ({$subquery})";

        // execute the query
        return NewsletterSubscriber::$db->fetch($sql, array($this->id));
    }

    /**
     * Sets the sent time on the newsletter
     */
    public function sent()
    {
        try {
            $this->set(array('sent' => time()));
            $this->save();
            event('newsletter.sent', $this);

        } catch (\Exception $e) {
            Error::exception($e);
        }


    }

    /**
     * Returns true if the newsletter is editable
     *
     * @return bool
     */
    public function isEditable()
    {
        return !$this->status || (!$this->sent && $this->status);
    }

    public function updateFromForm($input)
    {
        try {
            static::$db->pdo->beginTransaction();
            $this->set(
                array(
                    'template_id' => $input['template'],
                    'status'      => isset($input['status']) ? (int)$input['status'] : 0,
                    'notes'       => $input['notes'],
                )
            );

            $this->save();

            // create the content
            NewsletterContent::updateContent($this, $input['subject'], $input['content']);

            static::$db->pdo->commit();
            event('newsletter.updated', $this);

            return true;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return false;
    }
}
