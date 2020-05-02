<?php
/**
 * File: VisitorValidator.php
 * Created: 07-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Newsletter;

use Project\Models\NewsletterSubscriber;
use Story\Validator;

/**
 * Class VisitorValidator
 * @package Project\Support\Newsletter
 */
class VisitorValidator extends Validator
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
     * Unsubscribe validator
     *
     * @param $input
     * @return static
     */
    public static function unsubscribe($input)
    {
        $v = new static($input);

        $v->rule('required', 'email');
        $v->rule('email', 'email');
        $v->rule('exists', 'email', NewsletterSubscriber::getTable(), 'email')
            ->message(_('Email address is not valid.'));

        return $v;
    }

    /**
     * Unsubscribe validator
     *
     * @param $input
     * @return static
     */
    public static function subscribe($input)
    {
        $v = new static($input);

        $v->rule('required', 'email');
        $v->rule('email', 'email');

        $v->rule('unique', 'email', NewsletterSubscriber::getTable(), 'email', $input['email'])
            ->message(_('This email is already subscribed.'));

        return $v;
    }
}
