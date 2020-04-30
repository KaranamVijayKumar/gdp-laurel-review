<?php
/**
 * File: ExporterFactory.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Exporter;

use Goodby\CSV\Export\Standard\CsvFileObject;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Project\Models\Export;
use Story\Collection;

/**
 * Class ExporterFactory
 * @package Project\Services\Exporter
 */
class ExporterFactory implements ExporterFactoryInterface
{
    /**
     * @var Collection
     */
    public $exporters = array();

    /**
     * @var bool
     */
    public $exporters_loaded = false;

    /**
     * Returns all the exporters
     *
     * @return Collection
     */
    public function all()
    {
        // load exporters if not loaded
        if (!$this->exporters_loaded) {
            $this->loadExporters();
        }

        return $this->exporters;
    }

    /**
     * Loads the exporters
     *
     * @return Collection
     */
    public function loadExporters()
    {

        // get all the prefs classes from the preferences dir and add them to the preferences
        foreach (glob(__DIR__ . '/Exporters/*Exporter.php') as $key) {
            $name = 'Project\Services\Exporter\Exporters\\' . pathinfo($key, PATHINFO_FILENAME);
            $key = basename($key, 'Exporter.php');
            $class = new $name;

            if ($class instanceof ExporterInterface) {
                $this->exporters[$key] = new $name;
            }
        }
        $this->exporters = new Collection($this->exporters);
        $this->exporters_loaded = true;

        return $this->exporters;
    }

    /**
     * Returns the exporters category names
     *
     * @return array
     */
    public function getExporterNames()
    {
        // load exporters if not loaded
        if (!$this->exporters_loaded) {
            $this->loadExporters();
        }

        $list = array();
        /** @var ExporterInterface $exporter */
        foreach ($this->exporters as $key => $exporter) {
            $list[$key] = _($exporter->getCategoryName());
        }

        natsort($list);

        return $list;
    }

    /**
     * Returns the exporter columns
     *
     * @return array
     */
    public function getExporterColumns()
    {
        // load exporters if not loaded
        if (!$this->exporters_loaded) {
            $this->loadExporters();
        }

        $list = array();
        /** @var ExporterInterface $exporter */
        foreach ($this->exporters as $key => $exporter) {
            $list[$key] = $exporter->getColumns();
        }

        return $list;
    }

    /**
     * Returns the exporter by name
     *
     * @param $exporter
     *
     * @return ExporterInterface
     */
    public function get($exporter)
    {
        // load exporters if not loaded
        if (!$this->exporters_loaded) {
            $this->loadExporters();
        }

        return $this->exporters->getByIndex($exporter);
    }

    /**
     * Generates the export data
     *
     * @param array $data
     *
     * @throws InvalidExporterException
     *
     * @return \stdClass
     */
    public function generate(array $data)
    {
        // data must contain at least the exporter key with the exporter id
        if (!isset($data['exporter'])) {
            throw new InvalidExporterException('Invalid exporter selected.');
        }

        // get the export from the db
        /** @var Export $export */
        $export = Export::find((int)$data['exporter']);

        if (!$export) {
            throw new InvalidExporterException('Invalid exporter selected.');
        }

        // Based on the export we get the exporter and build the data
        $exporter = $this->getByExporter($export->exporter);

        return $exporter->build($export, $data);
    }

    /**
     * Returns the exporter by class name
     *
     * @param $name
     *
     * @return null|ExporterInterface
     */
    public function getByExporter($name)
    {
        // load exporters if not loaded
        if (!$this->exporters_loaded) {
            $this->loadExporters();
        }

        foreach ($this->exporters as $exporter) {
            if (get_class($exporter) === $name) {
                return $exporter;
            }
        }

        return null;
    }

    /**
     * Downloads the file
     *
     * @param string    $format
     * @param \stdClass $data
     *
     * @return mixed
     */
    public function download($format, \stdClass $data)
    {
        $fct = 'download' . studly($format);

        return call_user_func(array($this, $fct), $data);
    }

    /**
     * Download as csv
     *
     * @param \stdClass $data
     *
     * @throws \Exception
     */
    public function downloadCsv(\stdClass $data)
    {
        ini_set('memory_limit', '384M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);

        $filename = $this->buildCsvFile($data);
        $name = $data->name . '.csv';
        $file = new \SplFileInfo($filename);

        set_time_limit(0);
        ignore_user_abort(false);
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 0);

        $chunk = 10 * 1024 * 1024; // bytes per chunk (10 MB)

        $fh = fopen($file->getRealPath(), "rb");

        if ($fh === false) {
            throw new \Exception("Unable open file");
        }

        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
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

        // remove the temp file
        @unlink($filename);
        // and exit
        exit;
    }

    /**
     * Builds the csv file and returns the filename
     *
     * @param $data
     *
     * @return string
     */
    public function buildCsvFile($data)
    {
        $config = new ExporterConfig();
        $config->setToCharset('UTF-8');
        $config->setFromCharset('UTF-8');
        $config->setFileMode(CsvFileObject::FILE_MODE_WRITE);
        $exporter = new Exporter($config);

        $fn = SP . 'storage/tmp/tlr-export-' . random();

        $exporter->export($fn, array_merge(array($data->headers->all()), $data->payload->all()));

        return $fn;
    }
}
