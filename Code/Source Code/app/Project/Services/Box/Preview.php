<?php
/**
 * File: Preview.php
 * Created: 08-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Box;

use Carbon\Carbon;
use Project\Models\SubmissionFile;
use Story\Error;

/**
 * Class Preview
 *
 * @package Project\Services\Box
 */
class Preview extends \Project\Services\Preview
{

    /**
     * The application session
     *
     * @var \Story\Session
     */
    protected $app_session;

    /**
     * Current file session on box
     *
     * @var string
     */
    protected $session;

    /**
     * Box API key
     *
     * @var string
     */
    private $api_key;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->api_key = getenv('BOX_API_KEY');

        $this->app_session = app('session');

        ini_set('memory_limit', '384M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);

    }

    /**
     * Returns all the documents from box
     *
     * @throws BoxPreviewException
     * @return mixed
     */
    public function getDocuments()
    {

        $ch = curl_init('https://view-api.box.com/1/documents');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token ' . $this->api_key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $result = curl_exec($ch);

        $info = curl_getinfo($ch);
        if ($info['http_code'] !== 200) {
            throw new BoxPreviewException('Error getting the documents (' . $info['http_code'] . ')');
        }
        curl_close($ch);

        return json_decode($result);
    }

    /**
     * Uploads a file to box view
     *
     * @param \SplFileInfo $file
     * @param string       $thumbnails
     *
     * @throws BoxPreviewException
     * @return \stdClass
     */
    public function uploadDocument(\SplFileInfo $file, $thumbnails = '512x512')
    {

        $name = $file->getBasename('.' . $file->getExtension());


        $post = array(
            'name'       => $name,
            'thumbnails' => $thumbnails,
            'file'       => $this->getCurlValue($file)
        );

        $ch = curl_init('https://upload.view-api.box.com/1/documents');
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Token ' . $this->api_key,
                'Content-type: multipart/form-data',
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $document = curl_exec($ch);

        curl_close($ch);

        return json_decode($document);

    }

    /**
     * For curl file uploads we return the correct file upload data
     *
     * @param \SplFileInfo $file
     *
     * @return \CURLFile|string
     */
    public function getCurlValue(\SplFileInfo $file)
    {

        // get the mime for the file
        $mime = get_mime($file->getPathname());


        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create(
                $file->getPathname(),
                $mime,
                $file->getBasename('.' . $file->getExtension())
            );
        }

        // Use the old style if using an older version of PHP
        $value = "@{$file->getPathname()};filename=" . $file->getBasename();
        if ($mime) {
            $value .= ';type=' . $mime;
        }

        return $value;
    }

    /**
     * View a document
     *
     * @param $id
     *
     * @return bool
     */
    public function view($id)
    {

        // do we have cached version?
        if ($cachedAssets = $this->getCached($id)) {
            return (object)array('urls' => (object)array('assets' => $cachedAssets));
        }


        // check if the document exists
        $document = $this->getDocument($id);

        if (isset($document->type) && $document->type === 'error') {
            return $this->error($document->message, $id);
        }

        $method = 'get' . studly($document->status) . 'Status';

        return $this->$method($document);

    }

    /**
     * Returns the url for the cached assets
     *
     * @param $id
     *
     * @return bool|string
     */
    public function getCached($id)
    {

        // check if file info.json exists
        $path = SubmissionFile::generateAssetsPath($id) . '/info.json';

        if (is_readable($path)) {
            return action(
                '\Project\Controllers\Admin\Submissions\Assets',
                array(SubmissionFile::getStorageName($id))
            );
        }

        return false;
    }

    /**
     * Returns a document from box view
     *
     * @param string $id
     *
     * @throws BoxPreviewException
     * @return mixed
     */
    public function getDocument($id)
    {

        $ch = curl_init('https://view-api.box.com/1/documents/' . (string)$id);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token ' . $this->api_key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $result = curl_exec($ch);

        $info = curl_getinfo($ch);
        if ($info['http_code'] !== 200) {
            throw new BoxPreviewException('Error getting the document (' . $info['http_code'] . ')');
        }
        curl_close($ch);

        return json_decode($result);
    }

    /**
     * Displays an error
     *
     * @param $message
     * @param $id
     * @return string
     */
    private function error($message, $id)
    {

        return $this->__call('error', func_get_args());
    }

    /**
     * Get the unknown or error statuses for the file
     *
     * @param $name
     * @param $arguments
     *
     * @return string
     */
    public function __call($name, $arguments)
    {

        $msg = "An error occurred retrieving the document preview ($name) [" . implode(', ', (array)$arguments) . ']';

        log_message($msg);

        return '<p class="red">' . $msg . '</p>';
    }

    /**
     * If the document is done, we return the preview
     *
     * @param \stdClass $document
     *
     * @return string
     */
    protected function getDoneStatus(\stdClass $document)
    {

        $session = $this->createSession($document);

        // if we have a session coming from box, we attempt to save the assets locally
        if (isset($session->id)) {
            $session = $this->cacheDocumentAssets($document, $session);
        }

        return $session;
    }

    /**
     * Create a session for a document or use an existing session if not expired
     *
     * @param \stdClass $document
     * @param bool      $force Forces a new session
     *
     * @throws BoxPreviewException
     * @return mixed
     */
    private function createSession(\stdClass $document, $force = false)
    {

        // check if the session expired for the file
        $now = new Carbon(null, 'UTC');

        // check if the file session is expired or not
        if ($this->app_session->has($document->id) && !$force) {

            /** @var \stdClass $s */
            $s = $this->app_session->get($document->id);
            $expires = Carbon::createFromFormat('Y-m-d\TH:i:s.uP', $s->expires_at);

            if ($expires->gt($now)) {
                return $this->session = $s;
            }
        }

        // session expired or doesn't exists, we create a new one
        $ch = curl_init('https://view-api.box.com/1/sessions');

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Token ' . $this->api_key,
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $data = json_encode(
            array(
                'document_id' => $document->id,
                'duration'    => 60,
            )
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $session_data = curl_exec($ch);

        curl_close($ch);

        $this->session = json_decode($session_data);

        $this->app_session->put($document->id, $this->session);

        return $this->session;
    }

    /**
     * Cache the document assets locally
     *
     * @param \stdClass $document
     * @param \stdClass $session
     *
     * @return object|\stdClass
     */
    public function cacheDocumentAssets(\stdClass $document, \stdClass $session)
    {

        try {

            $directory = SubmissionFile::generateAssetsPath($document->id) . DIRECTORY_SEPARATOR;

            if (is_dir($directory)) {
                empty_dir($directory);
            } else {
                directory_is_writable($directory);
            }

            $base_url = $session->urls->assets;
            $fileCount = 0;
            // save the info
            $info_file = $this->saveAsset($base_url, 'info.json', $directory, $fileCount);
            $info = json_decode(file_get_contents($info_file), true);

            if (!isset($info['numpages'])) {
                empty_dir($directory, true);
                throw new \Exception('An error occured saving the cache for submission asset ' . $document->id);
            }

            $total = ($info['numpages'] * 2) + 2; // info.json, stylesheet.css + files * 2 (html, svg)
            // get the pages and svg
            for ($i = 1; $i <= $info['numpages']; $i++) {
                $this->saveAsset($base_url, 'text-' . $i . '.html', $directory, $fileCount);
                $this->saveAsset($base_url, 'page-' . $i . '.svg', $directory, $fileCount);
            }
            // save the stylesheet
            $this->saveAsset($base_url, 'stylesheet.css', $directory, $fileCount);

            // we saved all the assets when the filecount equals with the total
            if ($total === $fileCount) {
                // we can remove the file from box
                $this->deleteDocument($document);
            }
            // do we have cached version?
            $cachedAssets = $this->getCached($document->id);

            return (object)array('urls' => (object)array('assets' => $cachedAssets));

        } catch (\Exception $e) {
            Error::exception($e);
        }

        return $session;
    }

    /**
     * Save the asset for a file locally
     *
     * @param string $base_url  Base url of the assets
     * @param string $name      File name
     * @param string $directory directory where to save
     * @param int    $fileCount Asset file counter
     *
     * @return bool|string
     */
    private function saveAsset($base_url, $name, $directory, &$fileCount = 0)
    {

        $url = $base_url . $name;

        $ch = curl_init($url);
        $fp = fopen($directory . '/' . $name, "w");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_FILE, $fp);

        curl_exec($ch);

        $info = curl_getinfo($ch);
        curl_close($ch);
        fclose($fp);

        if ($info['http_code'] === 200) {
            $fileCount++;

            return $directory . '/' . $name;
        }

        @unlink($directory . '/' . $name);

        return false;
    }

    /**
     * Delete the document from box
     *
     * @param \stdClass $document
     *
     * @return mixed
     */
    public function deleteDocument(\stdClass $document)
    {

        $ch = curl_init('https://view-api.box.com/1/documents/' . (string)$document->id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token ' . $this->api_key));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $return = curl_exec($ch);

        curl_close($ch);

        return $return;
    }

    /**
     * Returns the processing status message for a document
     * @return string
     *
     */
    protected function getProcessingStatus()
    {

        return '<p class="gray"><em>'
        . _('We are generating the preview for the document. Please check back later or refresh the page.')
        . '</em></p>';
    }

    /**
     * Returns the queuing status message for a document
     * @return string
     * @internal param \stdClass $document
     *
     */
    protected function getQueuedStatus()
    {

        return '<p>' . _('The document is currently queued for preview processing. Please check back later.') . '</p>';
    }
}
