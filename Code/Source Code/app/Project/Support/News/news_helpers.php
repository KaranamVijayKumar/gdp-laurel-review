<?php
use Html2Text\Html2Text;
use Project\Support\Theme;

/**
 * File: news_helpers.php
 * Created: 02-12-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

/**
 * Returns the news sections
 *
 * @return array
 */
function get_news_sections()
{
    static $sections = false;

    if ($sections) {
        return $sections;
    }

    /** @var Theme $theme */
    $theme = app('theme');

    // get the sections for current theme
    $required = $theme->getOption('sections.news.editable', array('content'));
    $optional = $theme->getOption('sections.news.editable_optional', array());

    natsort($required);
    natsort($optional);

    return $sections = compact('required', 'optional');
}

/**
 * Filters the news article $_POST data and creates a text version for the sections
 *
 * @param array $input
 *
 * @return array
 */
function filter_news_input_fields(array $input)
{
    $return = array();
    // title
    $return['title'] = isset($input['title']) ? trim(html2text($input['title'])) : '';

    // status
    $return['status'] = isset($input['status']) ? (int) $input['status'] : 0;

    // newsletter
    $return['newsletter'] = isset($input['newsletter']) ? (int) $input['newsletter'] : 0;

    // Spin through the section categories and filter the input and also create a text version
    // from the input
    foreach (get_news_sections() as $section_type => $sections) {

        foreach ($sections as $name) {
            $input_name = $section_type . '-section-' . $name;
            $return[$input_name] = isset($input[$input_name]) ? trim($input[$input_name]) : '';
            $text = new Html2Text($return[$input_name]);
            $return[$input_name . '_text'] = $text->getText();
        }
    }


    return $return;
}
