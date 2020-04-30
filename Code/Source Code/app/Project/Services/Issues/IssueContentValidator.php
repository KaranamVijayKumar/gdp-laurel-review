<?php
/**
 * File: IssueContentValidator.php
 * Created: 20-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Issues;

use Story\Validator;

class IssueContentValidator extends Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {


        $data = $this->filterData($data);

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
        $data = array_map('trim', $data);

        return $data;
    }

    /**
     * Create issue validation rules
     *
     */
    public function addCreateRules()
    {

        // status
        $this->rule('in', 'status', array('1', '0'));

        // highlight
        $this->rule('in', 'highlight', array('1', '0'));

        // content
        $this->rule('required', 'content');
        $this->rule('lengthMax', 'content', 65535);

    }

    /**
     * Edit issue validation rules
     */
    public function addEditRules()
    {

        // status
        $this->rule('in', 'status', array('1', '0'));

        // highlight
        $this->rule('in', 'highlight', array('1', '0'));

        // content
        $this->rule('required', 'content');
        $this->rule('lengthMax', 'content', 65535);
    }
}
