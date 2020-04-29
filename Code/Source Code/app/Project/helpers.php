<?php
/**
 * File: helpers.php
 * Created: 30-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

use Project\Models\Chapbook;
use Project\Models\ChapbookFile;
use Project\Models\Issue;
use Project\Models\IssueFile;
use Project\Support\StoryEngine\ViewTemplateProvider;
use Story\Collection;
use StoryEngine\StoryEngine;

/**
 * Returns an admin path
 *
 * @param $name
 *
 * @return string
 */
function ap($name = '')
{

    return config('admin_path') . '/' . $name;
}

/**
 * Calculate the human-readable file size.
 *
 * @param  int $size
 *
 * @return string
 */
function get_file_size($size)
{

    static $units = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
    if (!$size || $size < 0) {
        return (int)$size . ' ' . $units[0];
    }

    return @round($size / pow(1024, ($key = floor(log($size, 1024)))), 2) . ' ' . $units[(int)$key];
}

/**
 * Downloads a file
 *
 * @param string $filename
 * @param string $name
 *
 * @throws Exception
 */
function download($filename, $name = '')
{

    $file = new \SplFileInfo($filename);

    set_time_limit(0);
    ignore_user_abort(false);
    ini_set('output_buffering', 0);
    ini_set('zlib.output_compression', 0);

    $chunk = 10 * 1024 * 1024; // bytes per chunk (10 MB)

    $fh = fopen($file->getRealPath(), "rb");

    if ($fh === false) {
        throw new Exception("Unable open file");
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . ($name ?: $file->getBasename()) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $file->getSize());

    // Repeat reading until EOF
    while (!feof($fh)) {
        echo fread($fh, $chunk);

        ob_flush(); // flush output
        flush();
    }

    exit;
}

/**
 * Returns the role permission name based on method and controller name
 *
 * @param string $method
 * @param string $controller
 * @param string $prefix
 *
 * @return string
 */
function get_permission_name($method, $controller, $prefix = 'Project\Controllers\\')
{

    return \Project\Support\Roles::getPermissionName($method, $controller, $prefix);
}

/**
 * Check access for the current name
 *
 * @param $name
 *
 * @return bool
 */
function has_access($name)
{

    return \Project\Support\Roles::hasAccess($name);
}


/**
 * Auto-linker
 *
 * Automatically links URL and Email addresses.
 * Note: There's a bit of extra code here to deal with
 * URLs or emails that end in a period.  We'll strip these
 * off and add them after the link.
 *
 * @access    public
 *
 * @param    string $str   the string
 * @param    string $type  the type: email, url, or both
 * @param    bool   $popup whether to create pop-up links
 *
 * @return    string
 */
function auto_link($str, $type = 'both', $popup = false)
{

    if ($type != 'email') {
        if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)) {
            $pop = ($popup == true) ? " target=\"_blank\" " : "";

            for ($i = 0; $i < count($matches['0']); $i++) {
                $period = '';
                if (preg_match("|\.$|", $matches['6'][$i])) {
                    $period = '.';
                    $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                }

                $str = str_replace(
                    $matches['0'][$i],
                    $matches['1'][$i] . '<a href="http' .
                    $matches['4'][$i] . '://' .
                    $matches['5'][$i] .
                    $matches['6'][$i] . '"' . $pop . '>http' .
                    $matches['4'][$i] . '://' .
                    $matches['5'][$i] .
                    $matches['6'][$i] . '</a>' .
                    $period,
                    $str
                );
            }
        }
    }

    if ($type != 'url') {
        if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches)) {
            for ($i = 0; $i < count($matches['0']); $i++) {
                $period = '';
                if (preg_match("|\.$|", $matches['3'][$i])) {
                    $period = '.';
                    $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                }

                $str = str_replace(
                    $matches['0'][$i],
                    '<a href="mailto:' . $matches['1'][$i] . '@' . $matches['2'][$i] . '.' . $matches['3'][$i] .
                    $period . '">' .
                    $matches['1'][$i] . '@' . $matches['2'][$i] . '.' . $matches['3'][$i] . $period . '</a>',
                    $str
                );
            }
        }
    }

    return $str;
}

/**
 * Creates a partial sql to search in the db
 *
 * @param        $query
 * @param array  $fields
 * @param string $i
 *
 * @return array
 */
function query_to_where($query, array $fields, $i = '`')
{

    // sanitize the query by removing extra spaces, trim and convert to lowercase
    $query = trim(mb_convert_case(preg_replace('/\s+/', ' ', $query), MB_CASE_LOWER));

    $where = '';

    $driver = app('db')->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    // explode the string
    $values = array();
    $exploded = explode(',', $query);
    $exploded = array_filter(array_map('trim', $exploded));

    foreach ($exploded as $subquery) {
        foreach ($fields as $field) {
            if ($driver == 'sqlite') {
                $where .= $i . $field . $i . ' LIKE "%" || ? || "%" OR ';
            } else {
                $where .= $i . $field . $i . ' LIKE CONCAT ("%", ?, "%") OR ';
            }
            $values[] = $subquery;
        }
    }

    $where = substr($where, 0, -3);

    return array('sql' => $where, 'values' => $values);
}

/**
 * Converts html string into text
 *
 * @param string $string
 *
 * @return string
 */
function html2text($string = '')
{

    if (!$string) {
        return $string;
    }
    $search = array(
        '@<script[^>]*?>.*?</script>@si',
        // Strip out javascript
        '@<style[^>]*?>.*?</style>@siU',
        // Strip style tags properly
        '@<[\/\!]*?[^<>]*?>@si',
        // Strip out HTML tags
        '@<![\s\S]*?--[ \t\n\r]*>@'
        // Strip multi-line comments including CDATA
    );

    $ret = preg_replace($search, '', $string);

    return trim(preg_replace('/\n+/', "\n", $ret));
}

/**
 * Ellipsize String
 *
 * This function will strip tags from a string, split it at its max_length and ellipsize
 *
 * @param    string  $str        string to ellipsize
 * @param    integer $max_length max length of string
 * @param    mixed   $position   int (1|0) or float, .5, .2, etc for position to split
 * @param    string  $ellipsis   ellipsis ; Default '...'
 *
 * @return    string        ellipsized string
 */

function ellipsize($str, $max_length, $position = 1, $ellipsis = ' &hellip; ')
{

    // Strip tags
    $str = trim(strip_tags($str));

    // Is the string long enough to ellipsize?
    if (mb_strlen($str) <= $max_length) {
        return $str;
    }

    $beg = mb_substr($str, 0, floor($max_length * $position));

    $position = ($position > 1) ? 1 : $position;

    if ($position === 1) {
        $end = mb_substr($str, 0, -($max_length - mb_strlen($beg)));
    } else {
        $end = mb_substr($str, -($max_length - mb_strlen($beg)));
    }

    return trim($beg . $ellipsis . $end);
}

/**
 * Empties the specified directory, removing all it's contents
 *
 * @param      $dir
 * @param bool $self_also If set to true, the directory is also removed
 */
function empty_dir($dir, $self_also = false)
{

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }

    if ($self_also) {
        rmdir($dir);
    }
}

/**
 * Convert a shorthand byte value from a PHP configuration directive to an integer value
 *
 * @param    string $value
 *
 * @return   int
 */
function convert_bytes($value)
{

    if (is_numeric($value)) {
        return $value;
    } else {
        $value_length = strlen($value);
        $qty = substr($value, 0, $value_length - 1);
        $unit = strtolower(substr($value, $value_length - 1));
        switch ($unit) {
            case 'k':
                $qty *= 1024;
                break;
            case 'm':
                $qty *= 1048576;
                break;
            case 'g':
                $qty *= 1073741824;
                break;
        }

        return $qty;
    }
}

/**
 * Returns the max allowed upload size in MB
 *
 * @return int
 */
function max_upload_size()
{

    static $max_size = false;

    if ($max_size !== false) {
        return $max_size;
    }

    $project = app('project');
    $max_upload = convert_bytes(ini_get('upload_max_filesize'));
    $max_post = convert_bytes(ini_get('post_max_size'));
    $memory_limit = convert_bytes(ini_get('memory_limit'));

    $config_max_upload_size = convert_bytes(config('max_upload_size'));
    $max_size = (int)min($max_upload, $max_post, $memory_limit, $config_max_upload_size);

    return $max_size;
}

/**
 * Determine if a given string ends with a given needle.
 *
 * @param string       $haystack
 * @param string|array $needles
 *
 * @return bool
 */
function ends_with($haystack, $needles)
{

    foreach ((array)$needles as $needle) {
        if ($needle == substr($haystack, strlen($haystack) - strlen($needle))) {
            return true;
        }
    }

    return false;
}

/**
 * Remove the <script> tags from a string
 *
 * @param string $str
 *
 * @return mixed
 */
function strip_script_tags($str)
{

    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $str);
}

/**
 * Create an HTML Link
 *
 * @param string $url        for the link
 * @param string $text       the link text
 * @param array  $attributes of additional tag settings
 *
 * @return string
 */
function link_to($url, $text = '', array $attributes = null)
{

    return \Story\HTML::link($url, $text, $attributes);
}

/**
 * Generates the page meta data
 *
 * @param Collection $collection
 * @param array      $extra
 *
 * @return string
 */
function page_meta(\Story\Collection $collection, array $extra = array())
{

    $items = array();

    foreach ($collection as $item) {
        $items[$item->name] = '<meta' .
            \Story\HTML::attributes(array('name' => $item->name, 'content' => $item->value)) .
            '>';
    }

    foreach ($extra as $key => $item) {
        $meta = '<meta' . \Story\HTML::attributes($item) . '>';
        if (is_int($key)) {
            $items[] = $meta;
        } else {
            $items[$key] = $meta;
        }
    }

    natsort($items);

    return implode("\n    ", $items) . "\n";
}

/**
 * Returns the latest news headlines
 * @return Collection
 * @internal param int $items
 */
function latest_news()
{

    $factory = new \Project\Support\News\NewsFactory();

    return $factory->latest();
}

/**
 * Creates a relative timestamp
 *
 * @param $timestamp
 *
 * @return string
 */
function diff_humans($timestamp)
{

    $created = \Carbon\Carbon::createFromTimestamp($timestamp);

    return array($created, $created->diffForHumans());
}

/**
 * Return the file extension based on mime type
 *
 * @param $path
 *
 * @return string
 */
function get_file_extension($path)
{

    $mime = get_mime($path);
    $mime_types = require(SP . 'config/mime_types.php');

    if (array_key_exists($mime, $mime_types)) {
        return $mime_types[$mime];
    }

    return '';
}

/**
 * Creates the cover page url for an issue
 *
 * @param stdClass|Issue $issue
 *
 * @return bool|string
 */
function issue_cover_page_url($issue)
{

    $cover_page = false;

    if (isset($issue->storage_name) && $issue->storage_name) {
        $cover_page = IssueFile::createCoverPageImageUrl($issue->storage_name);
    } elseif (isset($issue->cover_image) && $issue->cover_image instanceof IssueFile) {
        $cover_page = $issue->cover_image->getCoverPageImageUrl();
    }

    return $cover_page;
}

/**
 * Creates the cover page url for an chapbook
 *
 * @param stdClass|Issue $chapbook
 *
 * @return bool|string
 */
function chapbook_cover_page_url($chapbook)
{

    $cover_page = false;

    if (isset($chapbook->storage_name) && $chapbook->storage_name) {
        $cover_page = ChapbookFile::createCoverPageImageUrl($chapbook->storage_name);
    } elseif (isset($chapbook->cover_image) && $chapbook->cover_image instanceof ChapbookFile) {
        $cover_page = $chapbook->cover_image->getCoverPageImageUrl();
    }

    return $cover_page;
}

/**
 * Returns the chapbook price
 *
 * @param Chapbook $chapbook
 * @param Chapbook $latest
 *
 * @return string
 */
function get_chapbook_price(Chapbook $chapbook, Chapbook $latest = null)
{

    static $latest_chapbook = false;

    if ($latest) {
        $latest_chapbook = $latest;
    }
    if ($latest_chapbook === false) {
        $latest_chapbook = Chapbook::getLast();
    }

    if (!$chapbook->inventory) {
        return false;
    }

    return get_formatted_currency(
        config(
            $chapbook === $latest ? 'latest_chapbook_price' : 'back_chapbook_price'
        )
    );
}

/**
 * Returns the issue price
 *
 * @param Issue $issue
 * @param Issue $latest
 *
 * @return string
 */
function get_issue_price(Issue $issue, Issue $latest = null)
{

    static $latest_issue = false;

    if ($latest) {
        $latest_issue = $latest;
    }
    if ($latest_issue === false) {
        $latest_issue = Issue::getLast();
    }

    if (!$issue->inventory) {
        return false;
    }

    return get_formatted_currency(
        config(
            $issue === $latest ? 'latest_issue_price' : 'back_issue_price'
        )
    );
}

/**
 * Creates a link to the issue
 *
 * @param Issue $issue
 *
 * @return string
 */
function link_to_issue(Issue $issue)
{

    return \Story\URL::to(action('\Project\Controllers\Issues\Index', array($issue->slug)));
}

/**
 * Returns the localconv value of the entire array
 *
 * @param string $key
 *
 * @return array|mixed
 */
function get_locale_info($key = '')
{

    $locale_info = localeconv();

    if ($key) {
        return $locale_info[$key];
    }

    return $locale_info;
}

/**
 * Creates an acronym from passed string
 *
 * @param string $string
 * @param string $separator
 *
 * @return string
 */
function acronym($string, $separator = '')
{

    $words = preg_split("/\s+/", $string);
    $acronym = '';
    foreach ($words as $w) {
        $acronym .= $w[0] . $separator;
    }

    return $acronym;
}

/**
 * Returns a snippet's content
 *
 * @param $slug
 *
 * @return string|false
 */
function snippet($slug)
{
    static $snippets = array();
    // prevent infinite loop
    if (function_exists('ob_get_level') && ob_get_level() >= 12) {
        return false;
    }
    if (array_key_exists($slug, $snippets)) {
        return $snippets[$slug];
    }
    $snippet = \Project\Models\Snippet::get($slug);


    if ($snippet) {
        return $snippets[$snippet->slug] = $snippet->content;
    }

    return $snippets[$slug] = false;
}

/**
 * Displays a menu
 *
 * @param string $name
 * @param bool   $mode If set to true the menu will be horizontal
 *
 * @return bool|string
 */
function menu($name, $mode = false)
{
    global $selected;

    return \Project\Support\MenuFactory::get(
        $name,
        isset($selected) ? $selected : array(),
        $mode ? array('class' => 'nav') : array()
    );
}

/**
 * Initializes the storyengine
 */
function init_storyengine()
{
    $templateProvider = new ViewTemplateProvider;
    $parser = new \Project\Support\StoryEngine\Parser;

    $engine = new StoryEngine($templateProvider, array(false, 0));

    $engine->setParser($parser);
    $engine->parser->setAllowedFunctions(
        require SP . 'config/allowed_functions.php'
    );
    $engine->setDebug(config('debug'));

    global $app;
    $app['storyengine'] = $engine;
}

/**
 * Loads the redactor assets. Supports snippet, file, image
 * Usage: echo ws_redactor_assets('file', 'image')
 *
 * @return string
 */
function ws_redactor_assets()
{
    $args = array_unique(func_get_args());

    $data = array();
    foreach ($args as $name) {

        switch ($name) {
            case 'snippet':
                $data['snippetManagerJson'] = action('\Project\Controllers\Admin\Pages\EditorSnippetsIndex');
                break;
            case 'file':
                $data['fileUpload'] = action('\Project\Controllers\Admin\Files\EditorCreate');
                $data['fileManagerJson'] = action('\Project\Controllers\Admin\Files\EditorIndex');
                break;
            case 'image':
                $data['imageUpload'] = action('\Project\Controllers\Admin\Files\EditorCreate');
                $data['imageManagerJson'] = action('\Project\Controllers\Admin\Files\EditorIndex', array('images'));
                break;
            case 'link':
                $data['definedLinks'] = action('\Project\Controllers\Admin\Pages\EditorIndex');
        }
    }

    // Build the js

    $options = '';
    foreach ($data as $name => $value) {
        $options .= $name . ': "' . $value . '",';
    }

    return "\n<script>if (!ws) var ws = {};window.ws.redactor = {" . trim($options, ',') . "};</script>\n";
}

/**
 * @param float       $amount
 * @param string      $currency
 * @param null|string $locale
 *
 * @return string
 */
function get_formatted_currency($amount, $currency = 'USD', $locale = null)
{
    if (!$locale) {
        $locale = app('locale');
    }

    if (class_exists('\NumberFormatter')) {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($amount, $currency);
    } else {
        return number_format($amount, 2) . ' ' . $currency;
    }
}

/**
 * Returns the exporters by type
 *
 * @param $name
 *
 * @return array
 */
function get_exporters($name)
{
    /** @var \Project\Services\Exporter\ExporterFactoryInterface $exportFactory */
    $exportFactory = app('container')->make('\Project\Services\Exporter\ExporterFactoryInterface');

    // get the exporter
    $exporter = $exportFactory->get($name);

    if (!$exporter) {
        return array();
    }

    $exporter_name = get_class($exporter);

    return \Project\Models\Export::lists('id', 'name', array('status' => '1', 'exporter' => $exporter_name));


    return array();
}
