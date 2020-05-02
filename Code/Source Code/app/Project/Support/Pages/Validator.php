<?php
/**
 * File: Validator.php
 * Created: 21-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Pages;

use Html2Text\Html2Text;
use Project\Models\Page;

class Validator extends \Story\Validator
{
    public function __construct($data, $fields = array())
    {
        require_once SP . 'Project/Support/Pages/pages_helpers.php';
        $data = filter_pages_input_fields($data);
        parent::__construct($data, $fields); // TODO: Change the autogenerated stub
    }



    /**
     * Creates a page validator and applies the needed rules
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
        $validator->rule('lengthMax', 'title', 255);

        // status
        $validator->rule('required', 'status');
        $validator->rule('in', 'status', array(0, 1));

        // slug
        if ($input['page-type'] === 'system') {
            // system slug
            $validator->rule('required', 'system-slug')
                ->message(_('System page is required.'));
            $validator->rule('in', 'system-slug', array_keys(Page::getSystemPages()))
                ->message(_('Invalid system page selected.'));
        } else {
            $validator->rule('unique', 'slug', Page::getTable(), 'slug')
                ->message(_('Slug already taken.'));
        }




//        // sections
//        $all_sections = get_pages_sections();
//        // Required sections
//        foreach ($all_sections['required'] as $section_name) {
//
//            $name = 'required-section-' . $section_name;
//            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);
//            $message = _('%s is required.');
//            $validator->rule('required', $name)
//                ->message(sprintf($message, $field_name));
//            // limit
//            $message = _('%s must contain less then 16777215 characters.');
//            $validator->rule('lengthMax', $name, 16777215)
//                ->message(sprintf($message, $field_name));
//        }
//
//        // optional sections
//        foreach ($all_sections['optional'] as $section_name) {
//
//            $name = 'optional-section-' . $section_name;
//            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);
//
//            // limit
//            $message = _('%s must contain less then 16777215 characters.');
//            $validator->rule('lengthMax', $name, 16777215)
//                ->message(sprintf($message, $field_name));
//        }

        return $validator;
    }

    /**
     * Validates the slug json
     *
     * @param array $input
     * @param Page $page
     * @return \Story\Validator
     */
    public static function editSlug(array $input, Page $page)
    {

        // we convert the value into a slug and sanitize it
        if (isset($input['value'])) {
            $text = new Html2Text($input['value']);
            $value = str_replace("\u00a0", ' ', $text->getText());
            $value = slug($value, '-', '/');
            $value = preg_replace('/\/+/', '/', $value);
            $input['value'] = $value;
        }

        /** @var \Story\Validator $validator */
        $validator = new static($input);

        $validator->rule('required', 'value')
            ->message(_('Slug is invalid or it is required.'));

        $validator->rule('lengthMax', 'value', 200);

        $validator->rule('unique', 'value', Page::getTable(), 'slug', $input['value'], 'id', $page->id)
            ->message(_('Slug already taken.'));

        return $validator;
    }

    /**
     * Validates the title json
     *
     * @param array $input
     * @param Page $page
     * @return \Story\Validator
     */
    public static function editTitle(array $input, Page $page)
    {

        // we convert the value into a slug and sanitize it
        if (isset($input['value'])) {
            $text = new Html2Text($input['value']);
            $value = str_replace("\u00a0", ' ', $text->getText());

            $value = preg_replace('/\/+/', '/', $value);
            $input['value'] = $value;
        }

        /** @var \Story\Validator $validator */
        $validator = new static($input);

        $validator->rule('required', 'value')
            ->message(_('Title is invalid or it is required.'));

        $validator->rule('lengthMax', 'value', 200);

        return $validator;
    }

    public static function update($input, Page $page)
    {
        // Spin through the section categories and filter the input and also create a text version
        // from the input
        foreach (get_pages_sections() as $section_type => $sections) {

            foreach ($sections as $name) {
                $input_name = $section_type . '-section-' . $name;
                $return[$input_name] = isset($input[$input_name]) ? trim($input[$input_name]) : '';
                $text = new Html2Text($return[$input_name]);
                $input[$input_name . '_text'] = $text->getText();
            }
        }

        // remove the html from the meta
        if (isset($input['meta_name'])) {
            foreach ($input['meta_name'] as $key => $value) {
                $input['meta_name'][$key] = mb_substr(trim(html2text($value)), 0, 200);
            }
        }

        if (isset($input['meta_content'])) {
            foreach ($input['meta_content'] as $key => $value) {
                $input['meta_content'][$key] = mb_substr(trim(html2text($value)), 0, 200);
            }
        }

        /** @var \Story\Validator $validator */
        $validator = new static($input);

        // sections
        $all_sections = get_pages_sections();
        // Required sections
        foreach ($all_sections['required'] as $section_name) {

            $name = 'required-section-' . $section_name;
            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);
//            $message = _('%s is required.');
//            $validator->rule('required', $name)
//                ->message(sprintf($message, $field_name));
            // limit
            $message = _('%s must contain less then 16777215 characters.');
            $validator->rule('lengthMax', $name, 16777215)
                ->message(sprintf($message, $field_name));
        }

        // optional sections
        foreach ($all_sections['optional'] as $section_name) {

            $name = 'optional-section-' . $section_name;
            $field_name = mb_convert_case($section_name, MB_CASE_TITLE);

            // limit
            $message = _('%s must contain less then 16777215 characters.');
            $validator->rule('lengthMax', $name, 16777215)
                ->message(sprintf($message, $field_name));
        }

        // status
        $validator->rule('required', 'status');
        $validator->rule('in', 'status', array(0, 1));


        return $validator;
    }
}
