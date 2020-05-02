<?php
/**
 * File: ViewTemplateProvider.php
 * Created: 03-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\StoryEngine;

use Story\View;
use StoryEngine\Interfaces\TemplateProviderInterface;

/**
 * Class ViewTemplateProvider
 *
 * @package Project\Support\StoryEngine
 */
class ViewTemplateProvider implements TemplateProviderInterface
{

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $ext;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->directory = View::$directory;
        $this->ext = View::$ext;
    }
    /**
     * Returns the template content
     *
     * @param $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return file_get_contents($this->directory . $name . $this->ext);
    }
}
