<?php
/**
 * File: SubmissionExporter.php
 * Created: 29-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Exporter\Exporters;

use Carbon\Carbon;
use Html2Text\Html2Text;
use Project\Models\Export;
use Project\Models\Log;
use Project\Models\Submission;
use Project\Models\SubmissionCategory;
use Project\Models\SubmissionComment;
use Project\Models\SubmissionCoverletter;
use Project\Models\SubmissionFile;
use Project\Models\SubmissionPartial;
use Project\Services\Exporter\AbstractExporter;
use Project\Services\Exporter\ExporterInterface;
use Story\Collection;
use Story\ORM;

/**
 * Class SubmissionExporter
 * @package Project\Services\Exporter\Exporters
 */
class SubmissionExporter extends AbstractExporter implements ExporterInterface
{
    /**
     * Category name
     */
    const CATEGORY_NAME = 'Submission';

    /**
     * How many submisssions to export
     */
    const LIMIT = 5000;

    /**
     * Returns the category name
     *
     * @return string
     */
    public function getCategoryName()
    {
        return static::CATEGORY_NAME;
    }

    /**
     * Returns the available columns
     *
     * @return array
     */
    public function getColumns()
    {
        $cols = array(
            'activity'        => _('Activity (without comments)'),
            'category'        => _('Category'),
            'comments'        => _('Comments'),
            'coverletter'     => _('Cover letter'),
            'created'         => _('Created'),
            'dislikes'        => _('Dislikes'),
            'file_name'       => _('File name'),
            'likes'           => _('Likes'),
            'modified'        => _('Last Modified'),
            'name'            => _('Name'),
            'status'          => _('Status'),
            'submission_text' => _('Submission text'),
            'user'            => _('Author'),
            'user_address'    => _('Author Address'),
            'user_email'      => _('Author Email'),
            'user_name'       => _('Author Name'),
            'user_phone'      => _('Author Phone'),
            'withdrawals'     => _('Withdrawals'),
        );

        natsort($cols);

        return $cols;
    }

    /**
     * Builds the export data
     *
     * @param Export $export
     * @param array  $data
     *
     * @return \stdClass
     */
    public function build(Export $export, array $data)
    {
        $export_data = new \stdClass();
        // Build the name
        $export_data->name = $export->buildNameWithTimestamp();

        $export_data->headers = $this->buildHeaders($export->columns);

        $export_data->payload = new Collection;

        $order = null;
        if ($data['quantity'] == 'all') {
            $order = Submission::$db->i(Submission::getTable() . '.name') . ' ASC';
        }

        // no query, we list submissions by name
        if (!$data['query']) {
            $items = Submission::listSubmissions(
                $data['quantity'] == 'current' ? $data['page'] : 1,
                $data['quantity'] == 'current' ? config('per_page') : static::LIMIT,
                $data['status'],
                $data['category'],
                null,
                array('submission_status_id IS NOT NULL'),
                $order
            );
        } else {
            // we have query, get the submissions filtered
            $items = Submission::listSubmissionsByQuery(
                $data['query'],
                $data['quantity'] == 'current' ? $data['page'] : 1,
                $data['quantity'] == 'current' ? config('per_page') : static::LIMIT,
                $data['status'],
                $data['category'],
                array('submission_status_id IS NOT NULL'),
                $order
            );
        }

        if (!count($items['items'])) {
            return $export_data;
        }

        $this->getUsers($items['items']);

        // get the coverletter if needed
        if (in_array('coverletter', $export->columns)) {
            $this->getCoverletters($items['items']);
        }

        // get the file if needed
        if (in_array('file_name', $export->columns) || in_array('submission_text', $export->columns)) {
            $this->getFiles($items['items']);
        }

        // get the comments if needed
        if (in_array('comments', $export->columns)) {
            $this->getComments($items['items']);
        }

        // get the activity if needed
        if (in_array('activity', $export->columns)) {
            $this->getActivity($items['items']);
        }

        // get the withdrawals if needed
        if (in_array('withdrawals', $export->columns)) {
            $this->getWithdrawals($items['items']);
        }

        // build the payloads
        foreach ($items['items'] as $row) {

            $cells = array();
            // call for all columns the column function
            foreach ($export->columns as $column) {
                $fct = 'build' . studly($column) . 'Cell';
                $cells[] = call_user_func(array($this, $fct), $row);
            }

            $export_data->payload->push($cells);
        }

        return $export_data;
    }

    /**
     * Associates the coverletters with the submissions
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getCoverletters(Collection $items)
    {
        $submisssion_ids = $items->lists('id');

        if (!count($submisssion_ids)) {
            return new Collection;
        }

        $submisssion_ids = array_unique($submisssion_ids);

        if (!count($submisssion_ids)) {
            return new Collection();
        }
        $db = SubmissionCoverletter::$db;
        $coverletters = SubmissionCoverletter::all(
            array("{$db->i('submission_id')} IN (" . implode(',', $submisssion_ids) . ")")
        );


        foreach ($coverletters as $k => $coverletter) {
            $coverletters[$k] = new SubmissionCoverletter($coverletter);

            // get the order by id
            /** @var Submission $order */
            $item = $items->findBy('id', $coverletter->submission_id);

            if ($item) {
                $item->coverletter = $coverletters[$k];
            }
        }

        return $coverletters;
    }

    /**
     * Associates the coverletters with the submissions
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getFiles($items)
    {
        $submisssion_ids = $items->lists('id');

        if (!count($submisssion_ids)) {
            return new Collection;
        }

        $submisssion_ids = array_unique($submisssion_ids);

        if (!count($submisssion_ids)) {
            return new Collection();
        }

        $db = SubmissionFile::$db;
        $files = SubmissionFile::all(
            array("{$db->i('submission_id')} IN (" . implode(',', $submisssion_ids) . ")")
        );

        foreach ($files as $k => $file) {
            $files[$k] = new SubmissionCoverletter($file);

            // get the order by id
            /** @var Submission $order */
            $item = $items->findBy('id', $file->submission_id);

            if ($item) {
                $item->related['file'] = new SubmissionFile($file);
            }
        }

        return $files;
    }

    /**
     * Associates the coverletters with the submissions
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getComments($items)
    {
        $submisssion_ids = $items->lists('id');

        if (!count($submisssion_ids)) {
            return new Collection;
        }

        $submisssion_ids = array_unique($submisssion_ids);

        if (!count($submisssion_ids)) {
            return new Collection();
        }

        $db = SubmissionComment::$db;
        $comments = SubmissionComment::all(
            array("{$db->i('submission_id')} IN (" . implode(',', $submisssion_ids) . ")"),
            0,
            array('created', 'desc')
        );

        foreach ($comments as $k => $comment) {
            $comments[$k] = new SubmissionComment($comment);

            // get the order by id
            /** @var Submission $order */
            $item = $items->findBy('id', $comment->submission_id);

            if ($item) {

                if (!isset($item->related['comments'])) {
                    $item->related['comments'] = new Collection;
                }
                $item->related['comments']->push($comments[$k]);
            }
        }

        // get the users for the comments
        $comments = new Collection($comments);
        $this->getUsers($comments);

        return $comments;
    }

    /**
     * Associates the log with the submissions
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getActivity($items)
    {
        $submisssion_ids = $items->lists('id');

        if (!count($submisssion_ids)) {
            return new Collection;
        }

        $submisssion_ids = array_unique($submisssion_ids);

        if (!count($submisssion_ids)) {
            return new Collection();
        }

        $db = Log::$db;
        $logs = Log::all(
            array(
                "{$db->i('loggable_id')} IN (" . implode(',', $submisssion_ids) . ")",
                'loggable_type' => get_class($items->first())
            ),
            0,
            array('created', 'desc')
        );

        foreach ($logs as $k => $log) {
            $logs[$k] = new Log($log);

            // get the order by id
            /** @var Submission $order */
            $item = $items->findBy('id', $log->loggable_id);

            if ($item) {

                if (!isset($item->related['activity'])) {
                    $item->related['activity'] = new Collection;
                }
                $item->related['activity']->push($logs[$k]);
            }
        }

        return $logs;
    }

    /**
     * Associates the withdrawals with the submissions
     *
     * @param Collection $items
     *
     * @return Collection
     */
    public function getWithdrawals($items)
    {
        $submisssion_ids = $items->lists('id');

        if (!count($submisssion_ids)) {
            return new Collection;
        }

        $submisssion_ids = array_unique($submisssion_ids);

        if (!count($submisssion_ids)) {
            return new Collection();
        }

        $db = SubmissionPartial::$db;
        $withdrawals = SubmissionPartial::all(
            array("{$db->i('submission_id')} IN (" . implode(',', $submisssion_ids) . ")")
        );

        foreach ($withdrawals as $k => $withdrawal) {
            $withdrawals[$k] = new SubmissionPartial($withdrawal);

            // get the order by id
            /** @var Submission $order */
            $item = $items->findBy('id', $withdrawal->submission_id);

            if ($item) {
                $item->related['withdrawal'] = $withdrawals[$k];
            }
        }

        return $withdrawals;
    }

    /**
     * Builds the created cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildCreatedCell(ORM $item)
    {
        return $item->created->toDayDateTimeString();
    }

    /**
     * Builds the created cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildModifiedCell(ORM $item)
    {
        return $item->modified->toDayDateTimeString();
    }

    /**
     * Builds the category cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildCategoryCell(Submission $item)
    {
        return isset($item->category_name) ? $item->category_name : false;
    }

    /**
     * Builds the category cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildCommentsCell(Submission $item)
    {
        $return = '';

        if (!isset($item->comments) || !count($item->comments)) {
            return $return;
        }

        /** @var SubmissionComment $comment */
        foreach ($item->comments as $comment) {

            $return .= "* " . $item->modified->toDayDateTimeString() . ": ";

            $user = $comment->user;
            $name = $user->profiles->findBy('name', 'name');
            $return .= ($name ? $name->value : $user->email) . ": ";

            $text = new Html2Text($comment->message);
            $return .= $text->getText();

            $return .= "\n";
        }

        return trim($return);
    }

    /**
     * Builds the category cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildCoverletterCell(Submission $item)
    {
        if (!isset($item->coverletter) || !$item->coverletter) {
            return false;
        }

        $text = new Html2Text($item->coverletter->content);

        return trim($text->getText());
    }

    /**
     * Builds the name cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildNameCell(Submission $item)
    {
        return $item->name;
    }

    /**
     * Builds the file name cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildFileNameCell(Submission $item)
    {
        if (!isset($item->file) || !$item->file) {
            return false;
        }

        return $item->file->name;
    }

    /**
     * Builds the file name cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildLikesCell(Submission $item)
    {
        if (!isset($item->likes) || !$item->likes) {
            return '0';
        }

        return $item->likes;
    }

    /**
     * Builds the status cell
     *
     * @param ORM $item
     *
     * @return string
     */
    public function buildStatusCell(ORM $item)
    {
        return $item->status_name;
    }

    /**
     * Builds the withdrawals cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildWithdrawalsCell(Submission $item)
    {
        if (!isset($item->withdrawal)) {
            return '';
        }

        $text = new Html2Text($item->withdrawal->content);

        return trim($text->getText());
    }

    /**
     * Builds the withdrawals cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildActivityCell(Submission $item)
    {
        $return = '';

        if (!isset($item->activity) || !count($item->activity)) {
            return $return;
        }

        /** @var Log $activity */
        foreach ($item->activity as $activity) {

            $created = Carbon::createFromTimestamp($activity->created);
            $return .= "* " . $created->toDayDateTimeString() . ": ";
            $return .= str_replace('{user}', $activity->payload['user_fallback'], $activity->message);
            $return .= "\n";
        }

        return trim($return);
    }

    /**
     * Builds the submission text cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildSubmissionTextCell(Submission $item)
    {
        if (!isset($item->file) || !$item->file) {
            return false;
        }

        /** @var SubmissionFile $file */
        $file = $item->file;

        if (!$file->preview_key) {
            return false;
        }


        $path = SubmissionFile::generateAssetsPath($file->preview_key);


        // get the html files and get their content
        $content = '';
        foreach (glob($path . DIRECTORY_SEPARATOR . '*.html') as $file) {
            $content .= "\n\n" . file_get_contents($file);
        }
        $content = new Html2Text($content);

        return $content->getText();
    }

    /**
     * Builds the dislikes cell
     *
     * @param Submission $item
     *
     * @return string
     */
    public function buildDislikesCell(Submission $item)
    {
        if (!isset($item->dislikes) || !$item->dislikes) {
            return '0';
        }

        return $item->dislikes;
    }
}
