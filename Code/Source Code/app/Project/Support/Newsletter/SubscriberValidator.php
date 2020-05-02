<?php
/**
 * File: SubscriberValidator.php
 * Created: 07-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Newsletter;

use Project\Models\NewsletterSubscriber;
use Story\Validator;

class SubscriberValidator extends Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {
        $data['email'] = trim(html2text($data['email']));
        parent::__construct($data, $fields);
    }

    /**
     * Validates the create form
     *
     * @param array $input
     * @return static
     */
    public static function create(array $input)
    {

        $validator = new static($input);

        // email (not in)
        $validator->rule('required', 'email');
        // email
        $validator->rule('email', 'email');
        // unique
        $validator->rule('unique', 'email', NewsletterSubscriber::getTable(), 'email')
            ->message(_('User or email is already subscribed.'));

        return $validator;
    }

    /**
     * Validates the update form
     *
     * @param $input
     * @param NewsletterSubscriber $newsletterSubscriber
     * @return static
     */
    public static function update($input, NewsletterSubscriber $newsletterSubscriber)
    {
        $validator = new static($input);

        // email (not in)
        $validator->rule('required', 'email');
        // email
        $validator->rule('email', 'email');
        // unique
        $validator->rule(
            'unique',
            'email',
            NewsletterSubscriber::getTable(),
            'email',
            $input['email'],
            'id',
            $newsletterSubscriber->id
        )->message(_('E-mail is already subscribed.'));

        return $validator;
    }
}
