<?php
/**
 * File: AccountDeletedEvent.php
 * Created: 03-06-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Carbon\Carbon;
use Project\Models\SubmissionFile;
use Project\Models\Template;
use Project\Models\User;
use Story\Error;

/**
 * Class AccountDeletedEvent
 * @package Project\Support\Events\Handlers
 */
class AccountDeletedEvent extends AbstractEventHandler
{
    /**
     * Event handler constructor
     *
     * @param User $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
        // Load the user's profile
        $this->user->profiles->load();
        // load the template
        $this->template = Template::one(array('type' => 'account', 'name' => 'deleted'));
    }

    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        $user = $this->user;
        $db = $user::$db;

        try {
            $db->pdo->beginTransaction();
            // delete the carts
            $this->deleteCarts();

            // delete submissions
            $this->deleteSubmissions();

            // delete subscriptions
            $this->deleteSubscriptions();

            // send email
            $this->sendEmail();

            // Finally log the deletion
            $this->addLog();

            $db->pdo->commit();
        } catch (\Exception $e) {
            $db->pdo->rollBack();
            Error::exception($e);
        }

    }

    public function addLog()
    {
        // Log
        $this->log(
            $this->user,
            'User {user} deleted.',
            array(
                'user'          => $this->user->id,
                'user_fallback' => $this->user->profiles->findBy('name', 'name')->value .' (' . $this->user->email .')'
            )
        );
    }

    /**
     * Deletes the user carts
     */
    protected function deleteCarts()
    {
        //
    }

    /**
     * Deletes the user submissions
     */
    protected function deleteSubmissions()
    {
        // we delete the submission assets from box and from the storage
        $files = SubmissionFile::allForUser($this->user);

        list($uploaded_files, $assets, $preview_keys) = array_values($this->getSubmissionFiles($files));

        // delete the box files
        if (count($preview_keys)) {
            /** @var \Project\Services\Box\Preview $preview */
            $preview = app('container')->make('\Project\Services\PreviewInterface');

            foreach ($preview_keys as $key) {
                $document = (object)array('id' => $key);
                $preview->deleteDocument($document);
            }
        }

        // delete the assets
        if (count($assets)) {
            foreach ($assets as $asset_dir) {
                empty_dir($asset_dir, true);
            }
        }

        // and finally delete the uploaded files
        if (count($uploaded_files)) {
            foreach ($uploaded_files as $file) {
                @unlink($file);
            }
        }
    }

    /**
     * Deletes the user subscriptions
     */
    protected function deleteSubscriptions()
    {
        //
    }

    /**
     * Returns the submission files
     *
     * @param $files
     *
     * @return array
     */
    protected function getSubmissionFiles($files)
    {
        $uploaded_files = array();
        $assets = array();
        $preview_keys = array();

        // Spin through each file and build an array to delete from box and from storage
        /** @var SubmissionFile $file */
        foreach ($files as $file) {
            $uploaded_files[] = $file::getFilesPath($file->storage_name);
            $asset_dir = $file::generateAssetsPath($file->preview_key);
            if (is_dir($asset_dir)) {
                $assets[] = $asset_dir;
            } else {
                $preview_keys[] = $file->preview_key;
            }
        }

        return compact('uploaded_files', 'assets', 'preview_keys');
    }

    /**
     * Sends the notification event
     */
    protected function sendEmail()
    {
        // Replace the template message and subject
        $replacements = $this->createReplacements();
        list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);
        // Send email
        $this->mail($subject, $message, $this->user->email, $replacements['name']);
    }

    /**
     * Builds the replacemenents
     *
     * @return array
     */
    protected function createReplacements()
    {
        $name = $this->user->profiles->findBy('name', 'name');

        if (!$name) {
            $name = $this->user->email;
        } else {
            $name = $name->value;
        }

        $replacements = array(
            'name'     => $name,
            'date'     => Carbon::now()->toDayDateTimeString(),
        );

        return $replacements;
    }
}
