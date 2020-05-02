<?php
/**
 * File: Validator.php
 * Created: 04-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Subscriptions;

use Project\Controllers\Admin\Subscriptions\Export;
use Project\Models\Subscription;
use Project\Models\SubscriptionCategory;
use Project\Models\User;
use Project\Services\Exporter\ExporterFactoryInterface;

class Validator extends \Story\Validator
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
        // category exists
        $validator->rule('exists', 'category', SubscriptionCategory::getTable(), 'id', 'status', '=', '1');
        // user exists
        $validator->rule('required', 'user');
        $validator->rule('exists', 'user', User::getTable(), 'id');
        // description
        $validator->rule('lengthMax', 'description', 65535)
            ->message(_('Notes must contain less than 65535 characters.'));

        return $validator;
    }

    /**
     * Creates a new subscription validator and applies the needed rules
     *
     * @param array $input
     *
     * @return Validator
     */
    public static function update(array $input)
    {

        $validator = new static($input);

        // category
        $validator->rule('exists', 'category', SubscriptionCategory::getTable(), 'id', 'status', '=', '1');

        // description
        $validator->rule('lengthMax', 'description', 65535)
            ->message(_('Notes must contain less than 65535 characters.'));

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
        $class_name = get_class($exporter->get('Subscription'));
        $v->rule(
            'exists',
            'exporter',
            \Project\Models\Export::getTable(),
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

        // status
        $v->rule('required', 'status');
        $v->rule('in', 'status', array(Subscription::ALL, Subscription::ENABLED, Subscription::DISABLED));

        // expiration
        $v->rule('required', 'expiration');
        $v->rule(
            'in',
            'expiration',
            array(
                Subscription::ALL,
                Subscription::ACTIVE,
                Subscription::UPCOMING,
                Subscription::EXPIRED
            )
        );

        // page
        $v->rule('required', 'page');
        $v->rule('numeric', 'page');


        return $v;
    }
}
