<?php
/**
 * File: ExporterInterface.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */
namespace Project\Services\Exporter;

use Project\Models\Export;

/**
 * Class SubmissionExporter
 * @package Project\Services\Exporter\Exporters
 */
interface ExporterInterface
{
    /**
     * Returns the category name
     *
     * @return string
     */
    public function getCategoryName();

    /**
     * Returns the available columns
     *
     * @return array
     */
    public function getColumns();

    /**
     * Builds the export data
     *
     * @param Export $export
     * @param array  $data
     *
     * @return \stdClass
     */
    public function build(Export $export, array $data);
}
