<?php
/**
 * File: Validator.php
 * Created: 10-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\News;

use Project\Models\News;

/**
 * Class Validator
 *
 * @package Project\Support\News
 */
class Validator extends \Story\Validator
{
    /**
     * Creates a news article validator and applies the needed rules
     *
     * @param array $input
     *
     * @return \Story\Validator
     */
    public static function create(array $input)
    {

        /** @var \Story\Validator $validator */
        $validator = new static($input);

        // title
        $validator->rule('required', 'title');
        $validator->rule('unique', 'title', 'news_content', 'title')
            ->message(_('Article with the similar title already exists.'));

        // status
        $validator->rule('required', 'status');
        $validator->rule('in', 'status', array(0, 1));

        // newsletter
        $validator->rule('in', 'newsletter', array(0, 1));

        // sections
        $all_sections = get_news_sections();
        // Required sections
        foreach ($all_sections['required'] as $section_name) {

            $name =  'required-section-' . $section_name;
            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);
            $message = _('%s is required.');
            $validator->rule('required', $name)
                ->message(sprintf($message, $field_name));
            // limit
            $message = _('%s must contain less then 16777215 characters.');
            $validator->rule('lengthMax', $name, 16777215)
                ->message(sprintf($message, $field_name));
        }

        // optional sections
        foreach ($all_sections['optional'] as $section_name) {

            $name =  'optional-section-' . $section_name;
            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);

            // limit
            $message = _('%s must contain less then 16777215 characters.');
            $validator->rule('lengthMax', $name, 16777215)
                ->message(sprintf($message, $field_name));
        }


        return $validator;
    }

    /**
     * Creates an existing news article validator and applies the needed rules
     *
     * @param array $input
     * @param News  $article
     *
     * @return \Story\Validator
     */
    public static function edit(array $input, News $article)
    {
        /** @var \Story\Validator $validator */
        $validator = new static($input);

        // title
        $validator->rule('required', 'title');

        // check if the slug is unique
        $validator->rule('unique', 'slug', 'news', 'slug', $input['slug'], 'id', $article->id)
            ->message(_('Article with the similar title already exists.'));

        // status
        $validator->rule('required', 'status');
        $validator->rule('in', 'status', array(0, 1));

        // sections
        $all_sections = get_news_sections();
        // Required sections
        foreach ($all_sections['required'] as $section_name) {

            $name =  'required-section-' . $section_name;
            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);
            $message = _('%s is required.');
            $validator->rule('required', $name)
                ->message(sprintf($message, $field_name));
            // limit
            $message = _('%s must contain less then 16777215 characters.');
            $validator->rule('lengthMax', $name, 16777215)
                ->message(sprintf($message, $field_name));
        }

        // optional sections
        foreach ($all_sections['optional'] as $section_name) {

            $name =  'optional-section-' . $section_name;
            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);

            // limit
            $message = _('%s must contain less then 16777215 characters.');
            $validator->rule('lengthMax', $name, 16777215)
                ->message(sprintf($message, $field_name));
        }


        return $validator;
    }
}
