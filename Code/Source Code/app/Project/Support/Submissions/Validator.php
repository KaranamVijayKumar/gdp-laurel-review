<?php
/**
 * File: Validator.php
 * Created: 11-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Submissions;

use Project\Models\Export;
use Project\Models\Submission;
use Project\Models\SubmissionCategory;
use Project\Models\SubmissionStatus;
use Project\Services\Exporter\ExporterFactoryInterface;

/**
 * Class Validator
 * @package Project\Support\Submissions
 */
class Validator extends \Story\Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {

        $coverletter = isset($data['coverletter']) ? $data['coverletter'] : '';
        $data = array_map('html2text', $data);
        $data = array_map('trim', $data);

        $data = array_merge($data, $_FILES);
        $data['coverletter'] = trim($coverletter);

        parent::__construct($data, $fields);
    }

    /**
     * @param $input
     *
     * @return Validator
     */
    public static function create($input)
    {
        $validator = new Validator($input);

        $validator->rule('required', 'category')
            ->message(_('Category is required.'));

        $validator->rule('in', 'category', SubmissionCategory::lists('id', null, array('status' => '1')))
            ->message(_('Invalid category selected.'));

        $validator->rule('required', 'name');
        $validator->rule('lengthMax', 'name', 200);

        $validator->rule('required', 'coverletter')
            ->message(_('Cover letter is required.'));
        $validator->rule('lengthMax', 'coverletter', 65535);

        $validator->rule('upload', 'file', Submission::$fileTypes);

        $validator->rule('fileType', 'file', Submission::$fileTypes)
            ->message('Invalid file type supplied.');

        $validator->rule('maxFileSize', 'file', max_upload_size())
            ->message('Exceeded maximum file size limit.');

        return $validator;
    }

    /**
     * Validates the export parameters
     *
     * @param array                    $input
     * @param ExporterFactoryInterface $exporter
     *
     * @return static
     */
    public static function export(array $input, ExporterFactoryInterface $exporter)
    {
        $v = new static($input);

        // exporter
        $v->rule('required', 'exporter');
        $class_name = get_class($exporter->get('Submission'));
        $v->rule(
            'exists',
            'exporter',
            Export::getTable(),
            'id',
            'status',
            '=',
            '1',
            'exporter',
            '=',
            $class_name
        );

        // quantity
        $v->rule('required', 'quantity');
        $v->rule('in', 'quantity', array('all', 'current'));

        $statuses = array_keys(
            array_merge(
                array(SubmissionStatus::ALL => _('All statuses')),
                SubmissionStatus::lists('slug', 'name', null, 0, 0, array('order' => 'asc'))
            )
        );

        // categories
        $categories = array_keys(
            array_merge(
                array(SubmissionCategory::ALL => _('All categories')),
                SubmissionCategory::lists('slug', 'name', null, 0, 0, array('order' => 'asc'))
            )
        );

        // status
        $v->rule('required', 'status');
        $v->rule('in', 'status', $statuses);

        // category
        $v->rule('required', 'category');
        $v->rule('in', 'category', $categories);

        // page
        $v->rule('required', 'page');
        $v->rule('numeric', 'page');

        return $v;
    }
}
