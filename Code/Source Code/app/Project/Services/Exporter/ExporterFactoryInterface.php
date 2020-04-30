<?php
/**
 * File: ExporterFactoryInterface.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
namespace Project\Services\Exporter;

use Story\Collection;

/**
 * Class ExporterFactory
 * @package Project\Services\Exporter
 */
interface ExporterFactoryInterface
{
    /**
     * Returns the exporters category names
     *
     * @return array
     */
    public function getExporterNames();

    /**
     * Returns the exporter columns
     *
     * @return array
     */
    public function getExporterColumns();

    /**
     * Loads the exporters
     *
     * @return Collection
     */
    public function loadExporters();

    /**
     * Returns all the exporters
     *
     * @return Collection
     */
    public function all();

    /**
     * Returns the exporter by name
     *
     * @param $exporter
     *
     * @return ExporterInterface
     */
    public function get($exporter);

    /**
     * Returns the exporter by class name
     *
     * @param $name
     *
     * @return null|ExporterInterface
     */
    public function getByExporter($name);

    /**
     * Generates the export data
     *
     * @param array $data
     *
     * @throws InvalidExporterException
     *
     * @return \stdClass
     */
    public function generate(array $data);
}
