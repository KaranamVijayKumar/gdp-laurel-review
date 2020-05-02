<?php
/**
 * File: Validator.php
 * Created: 03-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Subscriptions;

use Project\Models\SubscriptionCategory;
use Story\Validator;

/**
 * Class Validator
 *
 * @package Project\Support\Subscription
 */
class CategoryValidator extends Validator
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
     * Creates a new subscription category validator and applies the needed rules
     *
     * @param array $input
     *
     * @return Validator
     */
    public static function create(array $input)
    {
        $validator = new static($input);
        $validator->rule('required', 'name');
        $validator->rule('lengthMax', 'name', 200);
        $validator->rule('unique', 'name', 'subscription_categories', 'name', $input['name'])
            ->message(_('{field} is taken.'));

        // description
        $validator->rule('lengthMax', 'description', 65535)
            ->message(_('Description must contain less than 65535 characters.'));

        // interval
        $validator->rule('required', 'interval');
        $validator->rule('in', 'interval', SubscriptionCategory::$intervals)
            ->message(_('Interval contains an invalid value.'));

        // amount
        $validator->rule('required', 'amount');
        $validator->rule('min', 'amount', 0.01);
        $validator->rule('max', 'amount', 999999999.99);

        return $validator;
    }

    /**
     * Creates an existing subscription category validator and applies the needed rules
     *
     * @param array $input
     * @param SubscriptionCategory  $category
     *
     * @return Validator
     */
    public static function edit(array $input, SubscriptionCategory $category)
    {
        array_walk_recursive($input, 'trim');
        array_walk_recursive($input, 'html2text');

        $validator = new CategoryValidator($input);
        $validator->rule('required', 'name');
        $validator->rule('lengthMax', 'name', 200);
        $validator->rule('unique', 'name', 'subscription_categories', 'name', $input['name'], 'id', $category->id)
            ->message(_('{field} is taken.'));

        // description
        $validator->rule('lengthMax', 'description', 65535)
            ->message(_('Description must contain less than 65535 characters.'));

        // interval
        $validator->rule('required', 'interval');
        $validator->rule('in', 'interval', SubscriptionCategory::$intervals)
            ->message(_('Interval contains an invalid value.'));

        // amount
        $validator->rule('required', 'amount');
        $validator->rule('min', 'amount', 0.01);
        $validator->rule('max', 'amount', 999999999.99);

        return $validator;
    }
}
