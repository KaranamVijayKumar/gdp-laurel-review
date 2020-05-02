<?php
/**
 * File: Validator.php
 * Created: 02-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Newsletter;

use Project\Models\Newsletter;
use Project\Models\Template;

/**
 * Class Validator
 * @package Project\Support\Newsletter
 */
class Validator extends \Story\Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {
        $data['subject'] = trim(html2text($data['subject']));
        $data['notes'] = trim(html2text($data['notes']));
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

        // subject
        $validator->rule('required', 'subject')->message(_('Subject is required.'));
        $validator->rule('lengthMax', 'subject', 255)
            ->message(_('Subject cannot be more than 255 characters.'));

        // content
        $validator->rule('required', 'content')->message(_('Content is required.'));
        $validator->rule('lengthMax', 'content', 65535)
            ->message(_('Content cannot be more than 65535 characters.'));

        // template
        $templates = Template::allWithCustom(
            array(
                'name' => Newsletter::TEMPLATE_NAME,
                'type' => 'newsletter'
            )
        );
        $validator->rule('in', 'template', $templates->lists('id'));

        $validator->rule('lengthMax', 'notes', 255)
            ->message(_('Notes cannot be more than 255 characters.'));

        return $validator;
    }

    public static function update($input)
    {
        return static::create($input);
    }
}
