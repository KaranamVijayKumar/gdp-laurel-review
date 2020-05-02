<?php
/**
 * File: Validator.php
 * Created: 31-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Snippets;

use Project\Models\Snippet;

class Validator extends \Story\Validator
{
    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {
        $data['slug'] = trim(html2text($data['slug']));
        $data['description'] = trim(html2text($data['description']));

        parent::__construct($data, $fields);
    }

    /**
     * Create validator
     *
     * @param array $input
     * @return static
     */
    public static function create(array $input)
    {

        $validator = new static($input);

        // template
        $validator->rule('required', 'slug')
            ->message(_('Name is required.'));
        $validator->rule('unique', 'slug', Snippet::getTable(), 'slug')
            ->message(_('Name already in use.'));

        // description
        $validator->rule('lengthMax', 'description', 255)
            ->message(_('Description cannot be more than 255 characters.'));

        // content
        $validator->rule('required', 'content')
            ->message(_('Content is required.'));
        $validator->rule('lengthMax', 'content', 65535);

        return $validator;
    }

    /**
     * Create validator
     *
     * @param array $input
     * @param Snippet $snippet
     * @return static
     */
    public static function update(array $input, Snippet $snippet)
    {

        $validator = new static($input);

        // template
        $validator->rule('required', 'slug')
            ->message(_('Name is required.'));
        $validator->rule('unique', 'slug', Snippet::getTable(), 'slug', $input['slug'], 'id', $snippet->id)
            ->message(_('Name already in use.'));

        // description
        $validator->rule('lengthMax', 'description', 255)
            ->message(_('Description cannot be more than 255 characters.'));

        // content
        $validator->rule('required', 'content')
            ->message(_('Content is required.'));
        $validator->rule('lengthMax', 'content', 65535);

        return $validator;
    }
}
