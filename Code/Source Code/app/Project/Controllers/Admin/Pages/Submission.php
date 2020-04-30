<?php
/**
 * File: Submission.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Carbon\Carbon;
use Project\Services\Billing\PaymentEventInterface;
use Project\Services\Cart\OrderableInterface;
use Project\Services\Cart\OrderItemProcessableInterface;
use Project\Services\Cart\OrderItemRefundableInterface;
use Project\Services\Cart\OrderItemVoidableInterface;
use Project\Support\Orders\LinkableInterface;
use Story\Auth;
use Story\Collection;
use Story\Error;
use Story\NotFoundException;
use Story\ORM;
use Story\Validator;
use StoryCart\CartItemRepository;

/**
 * Class Submission
 *
 * @property  $id
 * @property  $user_id
 * @package Project\Models
 */
class Submission extends ORM implements
    PaymentEventInterface,
    OrderableInterface,
    OrderItemProcessableInterface,
    LinkableInterface,
    OrderItemRefundableInterface,
    OrderItemVoidableInterface
{
    /**
     * Invoice prefix
     */
    const INVOICE_PREFIX = 'SBMN_';

    /**
     * Supported file types
     *
     * @var array
     */
    public static $fileTypes = array('doc', 'docx', 'pdf');

    /**
     * @var array
     */
    public static $has = array(
        'coverletter' => 'Project\Models\SubmissionCoverletter',
        'user'        => 'Project\Models\User',
        'file'        => 'Project\Models\SubmissionFile',
    );

    /**
     * @var array
     */
    public static $has_many = array(
        'comments'   => 'Project\Models\SubmissionComment',
        'emails'     => 'Project\Models\SubmissionEmail',
        'likes'      => 'Project\Models\SubmissionLike',
        'files'      => 'Project\Models\SubmissionFile',
        'votes'      => 'Project\Models\SubmissionVote',
        'categories' => 'Project\Models\SubmissionCategory',
    );

    /**
     * @var string
     */
    protected static $foreign_key = 'submission_id';

    /**
     * @var string
     */
    protected static $table = 'submissions';

    /**
     *
     * @var null|SubmissionStatus
     */
    public $submission_status = null;

    /**
     * @var null|SubmissionCoverletter
     */
    public $coverletter;

    /**
     * @var null|SubmissionFile
     */
    public $temp_file;

    /**
     * @var SubmissionCategory
     */
    public $category;

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id = 0)
    {

        require_once SP . 'Project/Support/Events/submission_events.php';

        return parent::__construct($id);
    }

    /**
     * Creates a new submission
     *
     * @param $input
     *
     * @return bool|string|static
     */
    public static function createSubmission($input)
    {

        $coverletter = $input['coverletter'];
        $input = array_map('html2text', $input);
        $input = array_map('trim', $input);

        $input = array_merge($input, $_FILES);
        $input['coverletter'] = trim($coverletter);


        $validator = new Validator($input);

        $validator->rule('required', 'name');
        $validator->rule('lengthMax', 'name', 200);

        $validator->rule('lengthMax', 'coverletter', 65535);

        $validator->rule('required', 'user');
        $validator->rule('in', 'user', array_keys(User::getAllByName()));

        $validator->rule('required', 'status');
        $validator->rule('in', 'status', SubmissionStatus::lists('id'));

        $validator->rule('required', 'category');
        $validator->rule('in', 'category', SubmissionCategory::lists('id'));

        $validator->rule('upload', 'file', static::$fileTypes);

        $validator->rule('fileType', 'file', static::$fileTypes)
            ->message('Invalid file type supplied.');

        $validator->rule('maxFileSize', 'file', max_upload_size())
            ->message('Exceeded maximum file size limit.');

        if ($validator->validate()) {
            try {

                $model = new static;

                $model->set(
                    array(
                        'user_id'                => (int)$input['user'],
                        'submission_category_id' => (int)$input['category'],
                        'submission_status_id'   => (int)$input['status'],
                        'name'                   => $input['name'],
                    )
                );

                $model->save();

                // add the file
                $model->changeFile(new SubmissionFile, $_FILES['file']);

                // add the coverletter
                if ($input['coverletter']) {
                    $coverletter = new SubmissionCoverletter;

                    $coverletter->set(
                        array(
                            'submission_id' => $model->id,
                            'user_id'       => $model->user_id,
                            'content'       => $input['coverletter']
                        )
                    );
                    $coverletter->save();
                }


                static::$db->pdo->commit();
                event('submission.created', array($model));

                return $model;
            } catch (\PDOException $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);

                return false;
            }
        }

        return $validator->errorsToNotification();
    }

    /**
     * Replaces the submission file with the uploaded one
     *
     * @param \Project\Models\SubmissionFile $file
     * @param array                          $inputFile
     *
     * @return bool
     */
    private function changeFile($file, $inputFile)
    {

        try {
            if (!static::$db->pdo->inTransaction()) {
                static::$db->pdo->beginTransaction();
            }

            if (isset($file->id)) {
                // check if we have a preview and delete the preview and the assets
                $file->deletePreview();

                // delete the uploaded file from the submissions
                $file->deleteFile();
            } else {
                // create a new submission file model
                $file = new SubmissionFile;
            }

            // move the uploaded file to the submissions
            $file->storeFile($inputFile);

            // upload the file to the preview
            $preview_key = $file->generatePreview();

            // set the name, storage_name, access_key, preview_key, mime
            $file->set(array('preview_key' => $preview_key, 'submission_id' => $this->id));

            // save the model
            $file->save();

            if (!static::$db->pdo->inTransaction()) {
                static::$db->pdo->commit();
            }

            return true;
        } catch (\Exception $e) {
            if (!static::$db->pdo->inTransaction()) {
                static::$db->pdo->rollBack();
            }

            Error::exception($e);

            return false;
        }
    }

    public static function createSubmissionForUser($input, Submission $model, User $user)
    {

//        $coverletter = $input['coverletter'];
//        $input = array_map('html2text', $input);
//        $input = array_map('trim', $input);
//
//        $input = array_merge($input, $_FILES);
//        $input['coverletter'] = trim($coverletter);
//
//        $validator = new Validator($input);
//
//        $validator->rule('required', 'category')
//            ->message(_('Category is required.'));
//
//        $validator->rule('in', 'category', SubmissionCategory::lists('id', null, array('status' => '1')))
//            ->message(_('Invalid category selected.'));
//
//        $validator->rule('required', 'name');
//        $validator->rule('lengthMax', 'name', 200);
//
//        $validator->rule('required', 'coverletter')
//            ->message(_('Cover letter is required.'));
//        $validator->rule('lengthMax', 'coverletter', 65535);
//
//        $validator->rule('upload', 'file', static::$fileTypes);
//
//        $validator->rule('fileType', 'file', static::$fileTypes)
//            ->message('Invalid file type supplied.');
//
//        $validator->rule('maxFileSize', 'file', max_upload_size())
//            ->message('Exceeded maximum file size limit.');

//        if ($validator->validate()) {

        try {
            static::$db->pdo->beginTransaction();

            // set the model
            $model->set(
                array(
                    'user_id'                => $user->id,
                    'submission_category_id' => (int)$input['category'],
                    'name'                   => $input['name'],
                    'submission_status_id'   => null
                )
            );
            $model->save();

            // add the file
            $model->changeFile(new SubmissionFile, $_FILES['file']);

            // add the coverletter
            if ($input['coverletter']) {
                $coverletter = new SubmissionCoverletter;

                $coverletter->set(
                    array(
                        'submission_id' => $model->id,
                        'user_id'       => $model->user_id,
                        'content'       => $input['coverletter']
                    )
                );
                $coverletter->save();
            }

            // set the event
            event('submission.tmp_created', array($model, $user));

            static::$db->pdo->commit();

            return $model;
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);

            return false;
        }


//        }

//        return $validator->errorsToNotification();
    }

    /**
     * List submissions but filters by query
     *
     * @param        $query
     * @param        $current
     * @param        $per_page
     *
     * @param        $status
     * @param        $category
     * @param array  $where
     *
     * @param string $order
     *
     * @return array
     */
    public static function listSubmissionsByQuery(
        $query,
        $current,
        $per_page,
        $status,
        $category,
        $where = null,
        $order = null
    ) {

        $tbl = static::$db->i(static::$table);
        $user_tbl = static::$db->i('users');
        $profile_tbl = static::$db->i('profiles');
        $i = static::$db->i;

        $fields = array("{$tbl}.{$i}name{$i}");
        // do we have access to view the author? if so, we search them by author also

        if (has_access('admin_submissions_view_author')) {
            $fields = array_merge($fields, array("{$user_tbl}.{$i}email{$i}", "{$profile_tbl}.{$i}value{$i}"));
        }
        $queryWhere = query_to_where($query, $fields, '');

        return static::listSubmissions($current, $per_page, $status, $category, $queryWhere, $where, $order);
    }

    /**
     * @param        $current
     * @param        $per_page
     *
     * @param        $status
     * @param        $category
     * @param null   $queryWhere
     * @param null   $extraWhere
     *
     *
     * @param string $order
     *
     * @return array
     */
    public static function listSubmissions(
        $current,
        $per_page,
        $status,
        $category,
        $queryWhere = null,
        $extraWhere = null,
        $order = null
    ) {

        try {

            static::$db->pdo->beginTransaction();

            $offset = $per_page * ($current - 1);

            $tbl = static::$db->i(static::$table);
            $status_tbl = static::$db->i('submission_statuses');
            $category_tbl = static::$db->i('submission_categories');
            $user_tbl = static::$db->i('users');
            $profile_tbl = static::$db->i('profiles');
            $i = static::$db->i;

            $where = array();
            $params = array();

            // check if we need to select a status
            if ($status != SubmissionStatus::ALL) {
                $where[] = "{$status_tbl}.{$i}slug{$i} = ?";
                $params[] = $status;
            }

            // check if we need a category
            if ($category != SubmissionCategory::ALL) {
                $where[] = "{$category_tbl}.{$i}slug{$i} = ?";
                $params[] = $category;
            }

            // do we have extra where?
            if (count($extraWhere)) {
                foreach ($extraWhere as $name => $value) {
                    if (is_int($name)) {
                        $where[] = $value;
                    } else {

                        $where[] = '`' . implode('`.`', explode('.', $name)) . "` = ?";
                        $params[] = $value;
                    }
                }
            }

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


            // get the submission data
            $sql = "SELECT {$tbl}.*,  " .
                // , status slug and name,
                "{$status_tbl}.{$i}slug{$i} as status_slug, {$status_tbl}.{$i}name{$i} as status_name, " .
                // category slug, category name
                "{$category_tbl}.{$i}slug{$i} as category_slug, {$category_tbl}.{$i}name{$i} as category_name, " .
                // author email and real name
                "{$user_tbl}.{$i}email{$i} as author_email,\n" .
                "{$profile_tbl}.{$i}value{$i} as author_name\n" .
                // from the submissions
                "FROM $tbl \n" .
                // join status table with submissions
                "LEFT JOIN {$status_tbl} ON {$tbl}.{$i}submission_status_id{$i} = {$status_tbl}.{$i}id{$i}\n" .
                // join category table with submissions
                "LEFT JOIN {$category_tbl} ON {$tbl}.{$i}submission_category_id{$i} = {$category_tbl}.{$i}id{$i}\n" .
                // join user table with submissions
                "LEFT JOIN {$user_tbl} ON {$tbl}.{$i}user_id{$i} = {$user_tbl}.{$i}id{$i}\n" .
                // join profile table with submissions
                "LEFT JOIN {$profile_tbl} ON {$tbl}.{$i}user_id{$i} = {$profile_tbl}.{$i}user_id{$i}\n" .
                // filter by status and/or category
                (count($where) ? "WHERE " . implode(" AND ", $where) : '') . "\n" .
                // add the search query
                $query . "\n" .
                // group by
                "GROUP BY {$tbl}.{$i}id{$i} \n" .
                // order by submissison created asc
                "ORDER BY " . ($order ? : "{$tbl}.{$i}created{$i} DESC") . " \n" .

                // limit
                "LIMIT $per_page OFFSET $offset";

            $items = static::$db->fetch($sql, $params);

            if ($items) {
                foreach ($items as $k => $row) {
                    $items[$k] = new Submission($row);
                }
            }

            $items = new Collection($items);

            static::addLikes($items);

            // count the total
            $sql = "SELECT COUNT(distinct {$tbl}.{$i}id{$i}) FROM $tbl \n" .
                "LEFT JOIN {$status_tbl} ON {$tbl}.{$i}submission_status_id{$i} = {$status_tbl}.{$i}id{$i}\n" .
                "LEFT JOIN {$category_tbl} ON {$tbl}.{$i}submission_category_id{$i} = {$category_tbl}.{$i}id{$i}\n" .
                "LEFT JOIN {$user_tbl} ON {$tbl}.{$i}user_id{$i} = {$user_tbl}.{$i}id{$i}\n" .
                "LEFT JOIN {$profile_tbl} ON {$tbl}.{$i}user_id{$i} = {$profile_tbl}.{$i}user_id{$i}\n" .
                (count($where) ? "WHERE " . implode(" AND ", $where) : '') . "\n" .
                $query . "\n";
            // group by
            $count = static::$db->column($sql, $params);

            static::$db->pdo->commit();

            return array('total' => $count, 'items' => $items);
        } catch (\Exception $e) {
            static::$db->pdo->rollBack();
            Error::exception($e);
        }

        return array('total' => 0, 'items' => new Collection());
    }

    /**
     * Adds the likes count to the items
     *
     * @param Collection $items
     *
     * @return bool
     */
    protected static function addLikes(Collection $items)
    {

        if (!count($items)) {
            return false;
        }

        $ids = $items->lists();

        $idPlaceholders = trim(str_repeat('?,', count($ids)), ',');

        $i = static::$db->i;
        $sql = ("SELECT {$i}value{$i}, {$i}submission_id{$i} " .
            "FROM {$i}submission_likes{$i} WHERE {$i}submission_id{$i} IN ({$idPlaceholders})");
        $likes = new Collection(static::$db->fetch($sql, $ids));

        $likeArray = array();
        foreach ($likes as $like) {

            if (!isset($likeArray[$like->submission_id]['likes'])) {
                $likeArray[$like->submission_id]['likes'] = 0;
            }
            if (!isset($likeArray[$like->submission_id]['dislikes'])) {
                $likeArray[$like->submission_id]['dislikes'] = 0;
            }

            if ($like->value === '1') {
                $likeArray[$like->submission_id]['likes']++;
            }

            if ($like->value === '-1') {
                $likeArray[$like->submission_id]['dislikes']++;
            }
        }

        foreach ($items as $item) {
            if (array_key_exists($item->id, $likeArray)) {
                $item->likes = isset($likeArray[$item->id]['likes']) ? $likeArray[$item->id]['likes'] : 0;
                $item->dislikes = isset($likeArray[$item->id]['dislikes']) ? $likeArray[$item->id]['dislikes'] : 0;
            }
        }

        return true;
    }

    /**
     * This function is called when a payment is updated that is related to a model
     *
     * @param int    $id
     * @param string $status
     *
     * @return bool
     */
    public static function payEvent($id, $status)
    {

        /** @var \Project\Services\Billing\Paypal\Payment $payment */
        $payment = app('container')->make('\Project\Services\Billing\PaymentInterface');

        // we update the submission based on the status
        $model = static::findOrFail($id);


        switch ($status) {
            case $payment::PAYMENT_STATUS_COMPLETED:

                // set status to new
                /** @var \stdClass $statusModel */
                $statusModel = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_NEW));

                $model->submission_status_id = $statusModel->id;

                $model->save();

                event('submission.created', array($model));

                return true;
                break;
            case $payment::PAYMENT_STATUS_CANCELED_REVERSAL:
            case $payment::PAYMENT_STATUS_DENIED:
            case $payment::PAYMENT_STATUS_EXPIRED:
            case $payment::PAYMENT_STATUS_FAILED:
            case $payment::PAYMENT_STATUS_REFUNDED:
            case $payment::PAYMENT_STATUS_REVERSED:

                // we remove the submission since the payment failed for some reason
                $model->submission_status_id = null;

                $model->save();

                return true;
                break;
        }

        return false;
    }

//    /**
//     * Remove submissions from the cart
//     */
//    public static function removeFromCart()
//    {
//
//        /** @var \Project\Services\Cart\Cart $cart */
//        $cart = app('container')->make('Project\Services\Cart\CartInterface');
//        foreach ($cart->all() as $item) {
//            if ($item->data['type'] === 'Submission') {
//                $cart->forget($item->name);
//            }
//        }
//    }

    /**
     * Create a temp submission
     *
     * @param array $data
     * @param User  $user
     *
     * @return static
     */
    public static function createTempSubmission(array $data, User $user)
    {
        // $model = new static;

        $id = 'temp_' . random(3);


        // set the model
        $attributes = array(
            'id'                     => $id,
            'user_id'                => $user->id,
            'submission_category_id' => (int)$data['category'],
            'name'                   => $data['name'],

        );

        /** @var Submission $model */
        $model = new static($attributes);

        // status
        $model->submission_status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_NEW));

        // coverletter
        $model->coverletter = new SubmissionCoverletter;

        $model->coverletter->set(
            array(
                'user_id' => $model->user_id,
                'content' => $data['coverletter']
            )
        );

        // store the temp file and get the model
        $model->temp_file = SubmissionFile::storeTempFile($_FILES['file']);

        // store the user
        $model->category = SubmissionCategory::one(array('id' => $model->submission_category_id, 'status' => '1'));

        return $model;
    }

    /**
     * Creates the cart item model from the cart item
     *
     * @param \StoryCart\CartItemRepository $item
     *
     * @return \StoryCart\OrderItemRepository
     */
    public static function createFromCart(CartItemRepository $item)
    {
        $model = new OrderItem;

        $item_data = $item->type_payload;
        $assets = $item->type_assets;
        $item_data->coverletter = $assets['coverletter'];
        $item_data->temp_file = $assets['files'];


        $model->set(
            array(
                'orderable_id'   => $item->type_id,
                'orderable_type' => $item->type,
                'item_data'      => $item_data,
                'quantity'       => $item->quantity,
                'price'          => $item->price,
                'tax'            => $item->tax,
                'currency'       => $item->currency,

            )
        );

        return $model;
    }

    /**
     * Returns the inventory
     *
     * @param int $id
     *
     * @return int
     */
    public static function getInventory($id)
    {
        return 2;
    }

    /**
     * Accepts the current submission
     *
     * @param $subject
     * @param $message
     *
     * @return string
     * @throws NotFoundException
     */
    public function actionAccepted($subject, $message)
    {

        // update status to accepted
        /** @var SubmissionStatus $status */
        $status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_ACCEPTED));

        if (!$status) {
            throw new NotFoundException('Status not found.');
        }
        $this->submission_status_id = $status->id;
        $this->save();

        // fire the event
        event('submission.accepted', array($this, Auth::user(), $subject, $message));

        // return message
        return _('Submission accepted.');
    }

    /**
     * Declines the current submission
     *
     * @param $subject
     * @param $message
     *
     * @return string
     * @throws NotFoundException
     */
    public function actionDeclined($subject, $message)
    {

        // update status to declined
        /** @var SubmissionStatus $status */
        $status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_DECLINED));

        if (!$status) {
            throw new NotFoundException('Status not found.');
        }
        $this->submission_status_id = $status->id;
        $this->save();

        // fire the event
        event('submission.declined', array($this, Auth::user(), $subject, $message));

        // return message
        return 'Submission declined.';
    }

    /**
     * Created accessor
     *
     * @param $value
     *
     * @return mixed
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
     * @return mixed
     */
    public function getModifiedAttribute($value)
    {

        return Carbon::createFromTimestamp($value);
    }

    public function updateSubmission($input)
    {

        $input = array_map('html2text', $input);
        $input = array_map('trim', $input);

        $input = array_merge($input, $_FILES);
        $validator = new Validator($input);

        $validator->rule('required', 'name');
        $validator->rule('lengthMax', 'name', 200);

        $validator->rule('required', 'status');
        $validator->rule('in', 'status', SubmissionStatus::lists('id'));

        $validator->rule('required', 'category');
        $validator->rule('in', 'category', SubmissionCategory::lists('id'));

        // validate submission file only when one was uploaded
        if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $validator->rule('fileType', 'file', static::$fileTypes)
                ->message('Invalid file type supplied.');

            $validator->rule('maxFileSize', 'file', max_upload_size())
                ->message('Exceeded maximum file size limit.');
        }

        if ($validator->validate()) {
            try {
                static::$db->pdo->beginTransaction();
                // update the name
                $this->name = $input['name'];

                // update status
                $this->submission_status_id = (int)$input['status'];

                // update category
                $this->submission_category_id = (int)$input['category'];

                // save
                $this->save();

                // replace file if needed
                if (file_exists($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {

                    $this->changeFile($this->file, $_FILES['file']);
                }

                static::$db->pdo->commit();
                event('submission.updated', array($this, Auth::user()));

                return true;
            } catch (\PDOException $e) {
                static::$db->pdo->rollBack();
                Error::exception($e);

                return false;
            }
        }


        return $validator->errorsToNotification();
    }

    /**
     * Withdraws the entire submission
     *
     * @param array $input
     * @param User  $author
     *
     * @return bool
     */
    public function withdrawEntire(array $input = array(), User $author = null)
    {
        if (!$author) {
            $author = Auth::user();
        }
        // get the status id
        /** @var SubmissionStatus $status */
        $status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_WITHDRAWN));

        $this->submission_status_id = $status->id;

        $this->save();

        event('submission.withdrawn', array($this, $author, $input));

        return true;
    }

    /**
     * Withdraws the part of the submission
     *
     * @param array             $input
     * @param User              $author
     *
     * @param SubmissionPartial $partial
     *
     * @return bool
     */
    public function withdrawPartial(array $input, User $author = null, SubmissionPartial $partial = null)
    {
        if (!$author) {
            $author = Auth::user();
        }

        // get the status id
        // we only change the status if status is new, since we don't want to mess with the in progress submission
        /** @var SubmissionStatus $new_status */
        $new_status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_NEW));

        if ($new_status->id === $this->submission_status_id) {
            /** @var SubmissionStatus $status */
            $status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_PARTIAL_WITHDRAWN));
            $this->submission_status_id = $status->id;
        }

        if (!$partial) {
            // Do we have partial data?
            $partial = SubmissionPartial::one(array('submission_id' => $this->id));

            if (!$partial) {
                $partial = new SubmissionPartial();
                $partial->set(array('submission_id' => $this->id));
            }
        }

        $partial->content = $input['withdraw_comment'];
        $partial->save();

        $this->save();

        event('submission.withdrawn.partially', array($this, $author));

        return true;
    }

    /**
     * Returns the order assets
     *
     * @return mixed
     */
    public function getCartAssets()
    {
        return array(
            'files'       => $this->temp_file,
            'coverletter' => $this->coverletter
        );
    }

    /**
     * Get the orderable name
     * @return string
     */
    public function getName()
    {
        return ($this->category ? $this->category->name . ': ' : '') . $this->name;
    }

    /**
     * Returns the order payload
     *
     * @return mixed
     */
    public function getCartPayload()
    {
        $model = new static($this->attributes);
        $model->category = $this->category;

        return $model;
    }

    /**
     * Returns the item price
     *
     * @return int|null
     */
    public function getPrice()
    {
        return $this->category->amount;
    }

    /**
     * Returns the order type like: Issue, etc.
     * @return string
     */
    public function getOrderType()
    {
        return _('Submission');
    }

    /**
     * Called when an item is removed from the cart
     *
     * @param CartItemRepository $model
     *
     * @return mixed
     */
    public function removeFromCart(CartItemRepository $model)
    {
        // we delete the submission temp file
        $assets = $model->type_assets;
        if (!isset($assets['files'])) {
            return true;
        }

        /** @var SubmissionFile $file */
        $file = $assets['files'];
        return $file->deleteTempFile();
    }

    /**
     * @return boolean
     */
    public function canLink()
    {
        return is_numeric($this->key());
    }

    /**
     * @return string
     */
    public function getAdminLink()
    {
        if (has_access('admin_submissions_show')) {
            return action('\Project\Controllers\Admin\Submissions\Show', array($this->key()));
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        if (has_access('submissions_show')) {
            return action('\Project\Controllers\Submissions\Show', array($this->key()));
        }

        return false;
    }


    /**
     * Processes the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function processOrderItem(OrderItem $item)
    {
        // first we check if the item was processed already
        if (is_numeric($this->key())) {
            return $item;
        }

        // So far this is a new submission, so we create it
        $model = new static;


        /** @var SubmissionStatus $status */
        $status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_NEW));
        $model->set(
            array(
                'submission_status_id'   => $status ? $status->key() : null,
                'submission_category_id' => $this->category->key(),
                'name'                   => $this->name,
                'user_id'                => $this->user_id
            )
        );

        $model->save(true);


        // set the submission_id for the coverletter
        $this->coverletter->set(array('submission_id' => $model->key()));
        $this->coverletter->save();

        // set the file
        $this->temp_file->moveTempFile();
        $this->temp_file->set(array('submission_id' => $model->key()));
        $this->temp_file->save();
        // upload the file to the preview
        $preview_key = $this->temp_file->generatePreview();
        // set the preview key
        $this->temp_file->set(array('preview_key' => $preview_key));
        $this->temp_file->save();

        $item->orderable_id = $model->key();
        $item->item_data = $model;

        $item->save();

        event('submission.created', array($model));

        return $item;
    }

    /**
     * Refunds the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function refundOrderItem(OrderItem $item)
    {
        $item_data = $item->item_data;
        // check if the item_data is a subscription, if it is, we continue, otherwise nothing to do
        if (!$item_data instanceof Submission) {
            return $item;
        }

        // we refund this model by simply setting the status to withdrawn
        /** @var SubmissionStatus $status */
        $status = SubmissionStatus::one(array('slug' => SubmissionStatus::STATUS_WITHDRAWN));

        if ($status) {
            $status = $status->key();
        } else {
            $status = null;
        }

        $submission = static::find($item->orderable_id);

        if ($submission) {
            $submission->submission_status_id = $status;

            if (!Auth::check()) {
                $order = $item->order;
                /** @var OrderUser $user */
                $user = $order->order_user->first();
                $user->load();
                $user = User::findOrFail($user->user_id);
            } else {
                $user = Auth::user();
            }
            event('submission.withdrawn', array($submission, $user));

            $submission->save();
        }


        $item->item_data = $submission;
        $item->save();
        return $item;
    }

    /**
     * Refunds the order item
     *
     * @param OrderItem $item
     *
     * @return OrderItem
     */
    public function voidOrderItem(OrderItem $item)
    {
        // we void this model by simply deleting the subscription,
        return $this->refundOrderItem($item);
    }
}
