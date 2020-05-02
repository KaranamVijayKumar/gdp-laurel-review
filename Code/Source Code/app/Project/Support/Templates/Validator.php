<?php
/**
 * File: Validator.php
 * Created: 26-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Templates;

use Project\Models\Template;

class Validator extends \Story\Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {
        $data['subject'] = trim(html2text($data['subject']));
        $data['message'] = trim(strip_script_tags($data['message']));

        if (isset($data['description'])) {
            $data['description'] = trim(html2text($data['description']));
        }

        $data['text'] = html2text($data['message']);

        parent::__construct($data, $fields);


    }

    public static function create(array $input)
    {

        $validator = new static($input);

        // template
        $validator->rule('required', 'default_template')
            ->message(_('Default template is required.'));
        $validator->rule('exists', 'default_template', Template::getTable(), 'id', 'locked', '=', '1');

        // description
        $validator->rule('required', 'description');
        $validator->rule('lengthMax', 'description', 255)
            ->message(_('Description cannot be more than 255 characters.'));

        // subject
        $validator->rule('required', 'subject')->message(_('Title / Subject is required.'));
        $validator->rule('lengthMax', 'subject', 255)
            ->message(_('Title / Subject cannot be more than 255 characters.'));

        // message
        $validator->rule('required', 'text')->message(_('Content / Message is required.'));
        $validator->rule('lengthMax', 'message', 65535)
            ->message(_('Content / Message cannot be more than 65535 characters.'));

        return $validator;
    }
    /**
     * Edit template validator rules
     *
     * @param $input
     * @return static
     */
    public static function editLocked(array $input)
    {

        $validator = new static($input);

        // subject
        $validator->rule('required', 'subject')->message(_('Title / Subject is required.'));
        $validator->rule('lengthMax', 'subject', 255)
            ->message(_('Title / Subject cannot be more than 255 characters.'));

        $validator->rule('required', 'text')->message(_('Content / Message is required.'));
        $validator->rule('lengthMax', 'message', 65535)
            ->message(_('Content / Message cannot be more than 65535 characters.'));

        return $validator;
    }

    /**
     * Edit template validator rules
     *
     * @param $input
     * @return static
     */
    public static function edit(array $input)
    {

        $validator = new static($input);

        // description
        $validator->rule('required', 'description');
        $validator->rule('lengthMax', 'description', 255)
            ->message(_('Description cannot be more than 255 characters.'));

        // subject
        $validator->rule('required', 'subject')->message(_('Title / Subject is required.'));
        $validator->rule('lengthMax', 'subject', 255)
            ->message(_('Title / Subject cannot be more than 255 characters.'));

        $validator->rule('required', 'text')->message(_('Content / Message is required.'));
        $validator->rule('lengthMax', 'message', 65535)
            ->message(_('Content / Message cannot be more than 65535 characters.'));

        return $validator;
    }
}
