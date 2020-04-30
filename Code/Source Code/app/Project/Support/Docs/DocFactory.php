<?php
/**
 * File: DocFactory.php
 * Created: 18-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Docs;

use Michelf\MarkdownExtra;
use Project\Support\MenuFactory;
use SplFileInfo;
use Story\HTML;

/**
 * Class DocFactory
 * @package Project\Support\Docs
 */
class DocFactory
{

    /**
     * Admin path prefix
     */
    const ADMIN_PREFIX = 'admin';

    /**
     * File extension
     */
    const EXT = 'md';
    /**
     * @var string
     */
    public $admin_path;
    /**
     * Requested doc path
     *
     * @var string
     */
    protected $request;

    /**
     * Mode: can be static::ADMIN_PREFIX or false
     * @var string
     */
    protected $mode = false;

    /**
     * @param $request
     */
    public function __construct($request = '')
    {

        $this->request = $request;
        $this->admin_path = config('admin_path');
    }

    /**
     * Builds the doc menu
     * @return array
     */
    public function buildMenu()
    {
        return $this->buildAdminMenus();
    }

    /**
     * @return array
     */
    protected function buildAdminMenus()
    {
        // we are using the menu admin-menu-main
        MenuFactory::setMenus();
        $menus = MenuFactory::$menus['admin-menu-main'];
        $this->mode = static::ADMIN_PREFIX;

        // except we replace the links with the help links
        reset($menus);
        while (list ($key, $menu) = each($menus)) {

            if (!isset($menu['docs'])) {
                unset($menus[$key]);
            } else {
                // replace the url with the help url
                $menus[$key]['name'] = HTML::link(
                    action('\Project\Controllers\Admin\Docs\Index', array($menu['docs'])),
                    html2text($menu['name'])
                );
                if (isset($menu['children'])) {

                    foreach ($menu['children'] as $c_key => $child) {
                        if (!isset($child['docs'])) {
                            unset($menus[$key]['children'][$c_key]);
                        } else {
                            $menus[$key]['children'][$c_key]['name'] = HTML::link(
                                action('\Project\Controllers\Admin\Docs\Index', array($child['docs'])),
                                html2text($child['name'])
                            );
                        }
                    }
                }
            }
        }

        return $menus;
    }

    /**
     * Returns the doc
     *
     * @return array
     */
    public function get()
    {
        $this->mode = static::ADMIN_PREFIX;

        // if no request, we show the index.md
        if (!$this->request) {
            return $this->generate('index');
        }

        $result = $this->generate($this->request);

        return $result;
    }

    /**
     * Generates the content
     *
     * @param $name
     *
     * @return array
     */
    public function generate($name)
    {

        $name = str_replace('/', '_', $name);
        $file = SP . 'docs/' . $this->mode . DIRECTORY_SEPARATOR . $name . '.' . static::EXT;


        $handle = @fopen($file, 'r');

        if ($handle) {
            // first line is the title
            $title = fgets($handle);

            // rest is the content
            $content = '';
            while (($line = fgets($handle)) !== false) {
                $content .= $line;
            }
            fclose($handle);


            // render the content with markdown

            $content = $this->parseContent($content);

            // based on the request we return the file
            return array($content, $title);
        }

        return array('Documentation is currently not available for the selected feature.', 'Not available');
    }

    /**
     * Parses the content
     *
     * @param $content
     *
     * @return string
     */
    public function parseContent($content)
    {
        $parser = new MarkdownExtra;
        // replace the admin/ to storyadmin
        $content = str_replace('admin/', $this->admin_path . '/', $content);

        return $parser->transform($content);
    }
}
