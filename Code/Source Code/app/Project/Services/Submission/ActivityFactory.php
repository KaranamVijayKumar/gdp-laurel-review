<?php
/**
 * File: ActivityFactory.php
 * Created: 14-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Submission;

use Carbon\Carbon;
use Project\Models\Log;
use Project\Models\Submission;
use Project\Models\SubmissionComment;
use Story\Collection;
use Story\HTML;
use Story\View;

class ActivityFactory
{

    /**
     * @var \Story\DB
     */
    protected $database;

    /**
     * @var Submission
     */
    protected $submission;

    public function __construct(Submission $submission)
    {

        $this->submission = $submission;
        $this->database = load_database();
    }

    public function get()
    {

        // get the logs
        $logs = $this->getLogs();

        // get the comments
        $comments = $this->getComments();

        // we are sorting the submission activities by created timestamp
        // and returning them in a new collection

        $merged_activity = array_merge($logs, $comments);

        usort($merged_activity, array($this, 'sortByCreatedDesc'));

        // set the dates in human readable format and return a collection of the activity
        foreach ($merged_activity as $activity) {
            $created = Carbon::createFromTimestamp($activity->created);
            $activity->human_created = $created->diffForHumans();
            $activity->created = $created->toDayDateTimeString();
        }

        return new Collection($merged_activity);
    }

    public function getLogs()
    {

        $logs = Log::all(
            array('loggable_id' => $this->submission->id, 'loggable_type' => get_class($this->submission)),
            0,
            0,
            array('created' => 'desc')
        );

        // get the users for the logs
        $user_ids = array();
        $payloads = array();
        foreach ($logs as $log) {

            $payloads[$log->id] = json_decode($log->payload);

            if (!isset($payloads[$log->id]->user) && !$payloads[$log->id]->user) {
                continue;
            }

            $user_ids[] = $payloads[$log->id]->user;
        }

        $user_ids = array_unique($user_ids);

        $profiles = $this->getUserProfileNames($user_ids);

        // start to generate the human readable logs
        $messages = array();

        $has_user_view_access = has_access('admin_users_edit');
        foreach ($logs as $log) {
            $msg = $log->message;
            // do we have a payload? if so we replace the replacements in the message with the payload values
            $msg = $this->replaceMessageKeys($payloads, $log, $profiles, $has_user_view_access, $msg);

            $messages[] = (object)array('msg' => $msg, 'created' => $log->created, 'type' => 'log');

        }

        return $messages;
    }

    /**
     * @param array $user_ids
     *
     * @return Collection
     */
    protected function getUserProfileNames(array $user_ids)
    {

        if (count($user_ids)) {
            $idPlaceholders = trim(str_repeat('?,', count($user_ids)), ',');
            // get the users
            $i = $this->database->i;
            $sql = ("SELECT {$i}user_id{$i},{$i}name{$i},{$i}value{$i} " .
                "FROM {$i}profiles{$i} WHERE {$i}name{$i} = ? AND {$i}user_id{$i} IN ({$idPlaceholders})");

            return new Collection($this->database->fetch($sql, array_merge(array('name'), $user_ids)));

        }
        return new Collection();
    }

    /**
     * Replaces the message keys with the payload values
     *
     * @param array         $payloads
     * @param \stdClass|Log $log
     * @param Collection    $profiles
     * @param bool          $has_user_view_access
     * @param string        $msg
     *
     * @return mixed
     */
    protected function replaceMessageKeys($payloads, $log, $profiles, $has_user_view_access, $msg)
    {

        if (isset($payloads[$log->id])) {

            foreach ($payloads[$log->id] as $key => $value) {

                if (ends_with($key, '_fallback')) {
                    continue;
                }


                // if the key = user we try to search in the profile or use the fallback
                if ($key === 'user') {
                    $replacement = $this->replaceUser($payloads, $log, $profiles, $has_user_view_access, $value, $key);

                } else {
                    $replacement = '<strong>' . _($value) . '</strong>';
                }

                $msg = str_replace('{' . $key . '}', $replacement, $msg);

            }
            return $msg;
        }
        return $msg;
    }

    /**
     * Replaces the user id with the user's name or liked user's name
     *
     * @param array         $payloads
     * @param \stdClass|Log $log
     * @param Collection    $profiles
     * @param bool          $has_user_view_access
     * @param string        $value
     * @param string        $key
     *
     * @return string
     */
    protected function replaceUser($payloads, $log, $profiles, $has_user_view_access, $value, $key)
    {

        $replacement = $profiles->findBy('user_id', $value);
        if ($replacement) {
            $replacement = $replacement->value;
        } else {
            $fallback_key = $key . '_fallback';
            $replacement = isset($payloads[$log->id]->$fallback_key) ?
                $payloads[$log->id]->$fallback_key : $value;
        }

        // do we have access to view the user?
        if ($has_user_view_access) {
            $replacement = HTML::link(
                action('\Project\Controllers\Admin\Users\Edit', array($value)),
                $replacement
            );
            return $replacement;
        } else {
            $replacement = '<strong>' . $replacement . '</strong>';
            return $replacement;
        }
    }

    public function getComments()
    {

        $comments = new Collection(
            SubmissionComment::all(
                array('submission_id' => $this->submission->id),
                0,
                0,
                array('created' => 'desc')
            )
        );

        $user_ids = $comments->lists('user_id');

        $profiles = $this->getUserProfileNames($user_ids);

        $messages = array();

        $has_user_view_access = has_access('admin_users_edit');

        $view = new View('admin/submissions/partials/message');
        foreach ($comments as $comment) {
            $msg = $comment->message;
            // do we have a payload? if so we replace the replacements in the message with the payload values

            $view->message = $msg;

            $user = $profiles->findBy('user_id', $comment->user_id);
            if ($user) {
                $user = $user->value;
            } else {
                $user = $comment->user_id;
            }

            if ($has_user_view_access) {
                $view->user = HTML::link(
                    action('\Project\Controllers\Admin\Users\Edit', array($comment->user_id)),
                    $user
                );
            } else {
                $view->user = $user;
            }

            $messages[] = (object)array('msg' => (string)$view, 'created' => $comment->created, 'type' => 'message');

        }

        return $messages;
    }

    public function sortByCreatedDesc($a, $b)
    {

        if ($a->created == $b->created) {
            return 0;
        }
        return ($a->created > $b->created) ? -1 : 1;
    }
}
