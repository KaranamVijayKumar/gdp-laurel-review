<?php
/**
 * File: IssueValidator.php
 * Created: 29-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Issues;

use Project\Models\Issue;
use Project\Models\IssueContent;
use Project\Models\IssueFile;
use Story\Validator;

/**
 * Class IssueValidator
 *
 * @package Project\Services\Issues
 */
class IssueValidator extends Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {


        $data = $this->filterData($data);

        $data['slug'] = isset($data['title']) ? slug($data['title']) : '';

        parent::__construct($data, $fields);
    }

    /**
     * Filters the input data
     *
     * @param $data
     *
     * @return array
     */
    protected function filterData($data)
    {

        // we remove the items that are html
        $htmlInput = array();
        foreach (IssueContent::$required_sections as $section) {
            if (isset($data[$section])) {
                $htmlInput[$section] = trim($data[$section]);
            }
        }
        foreach (IssueContent::$optional_toc_sections as $section) {

            $section = 'optional-section-' . slug($section);
            if (isset($data[$section])) {
                $htmlInput[$section] = trim($data[$section]);
            }
        }

        $data = array_map('html2text', $data);
        $data = array_map('trim', $data);

        $data = array_merge($data, $htmlInput);

        $data = array_merge($data, $_FILES);
        return $data;
    }

    /**
     * Create issue validation rules
     *
     */
    public function addCreateRules()
    {

        // slug
        $this->rule('unique', 'slug', Issue::getTable(), 'slug', $this->_fields['slug'])
            ->message('Issue with a similar title already exists.');

        // title
        $this->rule('required', 'title');
        $this->rule('lengthMax', 'title', 200);

        // short description
        $this->rule('required', 'short_description')
            ->message(_('Short description is required.'));
        $this->rule('lengthMax', 'short_description', 65535);

        // status
        $this->rule('in', 'status', array('1', '0'));

        // inventory
        if ($this->_fields['status']) {
            $this->rule('required', 'inventory')
                ->message('Stock is required.');
            $this->rule('min', 'inventory', 0);
            $this->rule('max', 'inventory', 1000000000);
        }

        // file
        $this->rule('upload', 'file', IssueFile::$coverPageFileTypes);
        $this->rule('fileType', 'file', IssueFile::$coverPageFileTypes)->message('Invalid file type supplied.');
        $this->rule('maxFileSize', 'file', max_upload_size())->message('Exceeded maximum file size limit.');

        // optional sections
        foreach (IssueContent::$optional_toc_sections as $section) {

            $this->rule('lengthMax', 'optional-section-' . slug($section), 65535)
                ->message(
                    sprintf(_('%s cannot be more than 65535 characters.'), mb_convert_case($section, MB_CASE_TITLE))
                );
        }

    }

    /**
     * @param Issue $issue
     */
    public function addEditRules(Issue $issue)
    {

        // slug
        // slug
        $this->rule('unique', 'slug', Issue::getTable(), 'slug', $this->_fields['slug'], 'id', $issue->id)
            ->message('Issue with a similar title already exists.');

        // title
        $this->rule('required', 'title');
        $this->rule('lengthMax', 'title', 200);

        // short description
        $this->rule('required', 'short_description')
            ->message(_('Short description is required.'));
        $this->rule('lengthMax', 'short_description', 65535);

        // status
        $this->rule('in', 'status', array('1', '0'));

        // inventory
        if ($this->_fields['status']) {
            $this->rule('required', 'inventory')
                ->message('Stock is required.');
            $this->rule('min', 'inventory', 0);
            $this->rule('max', 'inventory', 1000000000);
        }

        // file
        // validate submission file only when one was uploaded
        if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $this->rule('upload', 'file', IssueFile::$coverPageFileTypes);
            $this->rule('fileType', 'file', IssueFile::$coverPageFileTypes)->message('Invalid file type supplied.');
            $this->rule('maxFileSize', 'file', max_upload_size())->message('Exceeded maximum file size limit.');

        }

        // optional sections
        foreach (IssueContent::$optional_toc_sections as $section) {

            $this->rule('lengthMax', 'optional-section-' . slug($section), 65535)
                ->message(
                    sprintf(_('%s cannot be more than 65535 characters.'), mb_convert_case($section, MB_CASE_TITLE))
                );
        }
    }
}
