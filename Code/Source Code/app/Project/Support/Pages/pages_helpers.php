<?php
/**
 * File: pages_helpers.php
 * Created: 21-04-2015
 *
 * Admin pages helpers functions
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
use Html2Text\Html2Text;
use Project\Support\Theme;

/**
 * Returns the news sections
 *
 * @return array
 */
function get_pages_sections()
{
    static $sections = false;

    if ($sections) {
        return $sections;
    }

    /** @var Theme $theme */
    $theme = app('theme');

    // get the sections for current theme
    $required = $theme->getOption('sections.page.editable', array('content'));
    $optional = $theme->getOption('sections.page.editable_optional', array());

    natsort($required);
    natsort($optional);

    return $sections = compact('required', 'optional');
}

/**
 * Filters the pages $_POST data and creates a text version for the sections
 *
 * @param array $input
 *
 * @return array
 */
function filter_pages_input_fields(array $input)
{
    $return = array();

    // page type
    $return['page-type'] = isset($input['page-type']) ? $input['page-type'] : 'custom';

    // title
    $return['title'] = isset($input['title']) ? trim(html2text($input['title'])) : '';

    // status
    $return['status'] = isset($input['status']) ? (int) $input['status'] : 0;


    // system slug
    $return['system-slug'] = slug(isset($input['system-slug']) ? trim(html2text($input['system-slug'])) : '', '-', '/');
    $return['system-slug'] = preg_replace('/\/+/', '/', $return['system-slug']);
    $return['system-slug'] = trim($return['system-slug'], '/');

    // slug
    $return['slug'] = slug(isset($input['slug']) ? trim(html2text($input['slug'])) : '', '-', '/');

    if (!$return['slug']) {
        $return['slug'] = slug($return['title']);
    }

    $return['slug'] = preg_replace('/\/+/', '/', $return['slug']);
    $return['slug'] = trim($return['slug'], '/');



//    // Spin through the section categories and filter the input and also create a text version
//    // from the input
//    foreach (get_pages_sections() as $section_type => $sections) {
//
//        foreach ($sections as $name) {
//            $input_name = $section_type . '-section-' . $name;
//            $return[$input_name] = isset($input[$input_name]) ? trim($input[$input_name]) : '';
//            $text = new Html2Text($return[$input_name]);
//            $return[$input_name . '_text'] = $text->getText();
//        }
//    }

    // finally we merge the return with the rest of the input
    $return = array_merge($input, $return);

    return $return;
}

/**
 * Return the slug based on the input's system-slug or slug values
 *
 * @param array $input
 * @return string
 */
function get_page_slug_from_input(array $input)
{
    // system slug takes precedence over user entered slug
    if ($input['system-slug'] && $input['page-type'] === 'system') {
        return $input['system-slug'];
    }

    return $input['slug'];
}
