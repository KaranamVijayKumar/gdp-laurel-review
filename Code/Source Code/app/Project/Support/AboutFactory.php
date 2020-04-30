<?php
/**
 * File: AboutFactory.php
 * Created: 29-03-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support;

/**
 * Class AboutFactory
 * Generates the about page sections
 *
 * @package Wingsline\Rpadmin\About
 */
class AboutFactory
{

    /**
     * Packagist url
     */
    const PACKAGIST_URL = 'https://packagist.org/packages/';


    /**
     * @var array
     */
    protected static $extensions = array();


    /**
     * Extends the factory
     *
     * @param          $name
     * @param \Closure $function
     */
    public static function extend($name, \Closure $function)
    {
        static::$extensions[$name][] = $function;
    }

    /**
     * Returns the about factory
     */
    public function get()
    {
        $list = array();

        $list['__self'] = $this->own();
        $list[_('Composer Packages')] = $this->composerPackages();

        $extensionList = $this->callExtensions();

        $list = array_merge_recursive($extensionList, $list);
        uksort($list, 'strnatcmp');

        $list = array_merge(array('__self' => $this->own()), $list);

        return $list;
    }

    /**
     * About content about the current product
     *
     * @return array
     */
    public function own()
    {
        $list = array();
        // get the composer file
        if (!$composerFile = $this->getComposerFile(SP . 'composer.json')) {
            return $list;
        }
        $composer = json_decode($composerFile, true);

        if (!isset($composer['require'])) {
            return $list;
        }

        $list['description'] = $composer['description'];
        $list['license'] = $composer['license'];
        $list['authors'] = $composer['authors'];
        $list['support'] = $composer['support'];
        $list['version'] = VERSION;
        $list['name'] = $composer['name'];

        return $list;
    }

    /**
     * Returns the composer file contents
     *
     * @param string $name
     *
     * @return string
     */
    protected function getComposerFile($name)
    {
        return @file_get_contents($name);
    }

    /**
     * Returns an about list of composer packages
     *
     * @return array
     */
    public function composerPackages()
    {

        $list = array();

        $files = array_merge(
            glob(SP . 'vendor/*/*/composer.json'),
            glob(SP . 'vendor/*/*/*/*/*/composer.json')
        );

        natsort($files);
        foreach ($files as $file) {

            $list[] = $this->getComposerPackageAbout($file);

        }
        return $list;
    }

    /**
     * Returns an about array about a specific vendor package
     *
     * @param $packageName
     * @return array|bool
     * @internal param $version
     *
     */
    protected function getComposerPackageAbout($packageName)
    {
        $composerFile = $packageName;


        if ($contents = $this->getComposerFile($composerFile)) {
            $contentsDecoded = json_decode($contents, true);
        } else {
            return false;
        }


        if ($contentsDecoded) {

            return array(
                'url'         => static::PACKAGIST_URL . $packageName,
                'name'        => isset($contentsDecoded['name']) ? $contentsDecoded['name'] : e($packageName),
                'description' => isset($contentsDecoded['description']) ? e($contentsDecoded['description']) : '',
                'authors'     => isset($contentsDecoded['authors']) ? $contentsDecoded['authors'] : '',
            );
        }

        return false;
    }

    /**
     * Calls all the extensions
     *
     * @return array
     */
    protected function callExtensions()
    {

        $extensionList = array();

        foreach (static::$extensions as $name => $extension) {

            $sublist = array();
            foreach ((array)$extension as $ext) {
                $sublist[] = call_user_func($ext);
            }
            $extensionList[$name] = $sublist;
        }

        return $extensionList;
    }
}
