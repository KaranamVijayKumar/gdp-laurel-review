<?php
/**
 * File: IssueTocValidator.php
 * Created: 04-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Issues;

use Project\Models\Issue;
use Story\Validator;

class IssueTocValidator extends Validator
{
    /**
     * Constructor
     *
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
     * @param array $data
     *
     * @return array
     */
    protected function filterData(array $data)
    {

        return $data;
    }

    /**
     * Add the toc validation rules
     *
     * @param Issue $issue
     */
    public function addRules(Issue $issue)
    {
        //
    }
}
