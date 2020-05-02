<?php
/**
 * File: SubscriptionValidator.php
 * Created: 16-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Subscriptions;

use Project\Models\SubscriptionCategory;

/**
 * Class SubscriptionValidator
 *
 * @package Project\Support\Subscriptions
 */
class SubscriptionValidator extends Validator
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
     * Creates a new subscription validator and applies the needed rules
     *
     * @param array $input
     *
     * @return Validator
     */
    public static function create(array $input)
    {
        $validator = new static($input);

        // required
        $validator->rule('required', 'category')
            ->message(_('Category is required.'));

        // category exists
        $validator->rule('exists', 'category', SubscriptionCategory::getTable(), 'id', 'status', '=', '1')
            ->message(_('Invalid category selected.'));

        return $validator;
    }
}
