<?php
/**
 * File: Theme.php
 * Created: 25-11-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

/**
 * Class Theme
 * Handles the theme related operations and gets the theme options
 *
 * @package Project\Support
 */
class Theme
{

    /**
     * @var \stdClass
     */
    protected $config;

    /**
     * It is set to true when the theme config is loaded
     *
     * @var bool
     */
    protected $configLoaded = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $themes_path;

    /**
     * @param string $name Theme name
     */
    public function __construct($name)
    {
        // Set the theme name
        $this->setName($name);

        // and theme path
        $this->setThemesPath(SP . 'themes' . DS . $this->getName() . DS);

        $this->config = new \stdClass;
        // load the config
        $this->loadConfig();
    }

    /**
     * Gets the theme name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the theme name
     *
     * @param string $name
     */
    public function setName($name)
    {

        $this->name = (string) $name;
    }

    /**
     * @return mixed
     */
    public function getThemesPath()
    {

        return $this->themes_path;
    }

    /**
     * @param mixed $themes_path
     */
    public function setThemesPath($themes_path)
    {

        $this->themes_path = $themes_path;
    }

    /**
     *
     * @param      $name
     * @param null $default
     *
     * @return array|null|\stdClass
     */
    public function getOption($name, $default = null)
    {
        // explode the name
        $array = explode('.', $name);

        $return = null;

        foreach ($array as $index => $key) {

            $data = !$index ? $this->config : $return;

            $return = $this->getData($data, $key);

        }

        if (!is_null($return)) {
            return $return;
        }

        return $default;
    }

    /**
     * Loads the config if exists
     */
    protected function loadConfig()
    {
        if ($this->configLoaded) {
            return $this->configLoaded;
        }

        $file = $this->getThemesPath() . 'theme.json';

        if (realpath($file)) {
            $this->config = json_decode(file_get_contents($file));
            return $this->configLoaded = true;
        }

        return $this->configLoaded;
    }

    /**
     * Returns the property or value by name from the data
     *
     * @param array|\stdClass $data
     * @param string $name
     *
     * @return null|array|\stdClass
     */
    private function getData($data, $name)
    {
        if (is_null($data)) {
            return null;
        }

        if (is_array($data) && array_key_exists($name, $data)) {
            return $data[$name];
        } elseif (is_object($data) && property_exists($data, $name)) {
            return $data->$name;
        }

        return null;
    }
}
