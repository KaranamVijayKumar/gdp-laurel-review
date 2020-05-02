<?php
/**
 * File: WithdrawValidator.php
 * Created: 25-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Submissions;

use Story\Validator;

class WithdrawValidator extends Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {

        array_walk_recursive($data, 'trim');
        array_walk_recursive($data, 'html2text');
        parent::__construct($data, $fields);
    }

    /**
     * Validates the entire withdrawal
     *
     * @param array $input
     *
     * @return Validator
     */
    public static function entire(array $input)
    {

        $validator = new static($input);

        /** @noinspection PhpUndefinedMethodInspection */
        $validator->rule('required', 'withdraw')
            ->message('The withdrawal checkbox must be checked.');
        /** @noinspection PhpUndefinedMethodInspection */
        $validator->rule('accepted', 'withdraw')
            ->message('The withdrawal checkbox must be checked.');

        return $validator;
    }

    public static function partial(array $input)
    {
        $validator = new static($input);

        // comment
        /** @noinspection PhpUndefinedMethodInspection */
        $validator->rule('required', 'withdraw_comment')
            ->message(_('Withdraw titles are required.'));
        /** @noinspection PhpUndefinedMethodInspection */
        $validator->rule('lengthMax', 'description', 65535)
            ->message(_('Withdraw titles must contain less than 65535 characters.'));

        // checkbox
        /** @noinspection PhpUndefinedMethodInspection */
        $validator->rule('required', 'withdraw')
            ->message('The partial withdrawal checkbox must be checked.');
        /** @noinspection PhpUndefinedMethodInspection */
        $validator->rule('accepted', 'withdraw')
            ->message('The partial withdrawal checkbox must be checked.');

        return $validator;
    }
}
