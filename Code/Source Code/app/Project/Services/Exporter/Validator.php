<?php
/**
 * File: Validator.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Exporter;

class Validator extends \Story\Validator
{

    /**
     * @param array $data
     * @param array $fields
     */
    public function __construct($data, $fields = array())
    {
        $data['name'] = trim(html2text($data['name']));
        $data['description'] = trim(html2text($data['description']));
        $data['status'] = trim(html2text($data['status']));
        $data['exporter'] = trim(html2text($data['exporter']));

        if (isset($data['columns'])) {
            $data['columns'] = array_map('html2text', $data['columns']);
        }

        parent::__construct($data, $fields);
    }

    /**
     * Validates the create exporter
     *
     * @param                                          $data
     *
     * @param ExporterFactoryInterface                 $exporterFactory
     *
     * @return static
     */
    public static function create($data, ExporterFactoryInterface $exporterFactory)
    {
        $v = new static($data);
        $v->addCustomRules();
        // name
        $v->rule('required', 'name');
        $v->rule('lengthMax', 'name', 200);

        // status
        $v->rule('required', 'status');
        $v->rule('in', 'status', array(0, 1));

        // description
        $v->rule('lengthMax', 'description', 65535);

        $exporter_names = $exporterFactory->getExporterNames();
        // exporter
        $v->rule('required', 'exporter');
        $v->rule('in', 'exporter', array_keys($exporter_names))
            ->message('Type is required.');

        // columns
        $v->rule('required', 'columns')
            ->message('Add at least one column.');

        // depending on the column we check if all are valid exporters
        if (isset($data['exporter']) &&
            in_array($data['exporter'], array_keys($exporter_names)) && isset($data['columns'])
        ) {

            /** @var ExporterInterface $exporter */
            $exporter = $exporterFactory->all()->getByIndex($data['exporter']);

            // columns

            $v->rule('allColumnsIn', 'columns', array_keys($exporter->getColumns()));
        }

        return $v;
    }

    /**
     * Adds the custom rules
     *
     */
    public function addCustomRules()
    {
        // all columns must be in the valid column array
        static::addRule(
            'allColumnsIn',
            function ($field, $value, array $params) {

                if (!is_array($value)) {
                    return false;
                }
                foreach ($value as $v) {
                    if (!in_array($v, $params[0])) {
                        return false;
                    }
                }

                return true;
            },
            _('One or more columns contain an invalid value.')
        );
    }

    /**
     * Validates the create exporter
     *
     * @param                                          $data
     *
     * @param ExporterFactoryInterface                 $exporterFactory
     *
     * @return static
     */
    public static function edit($data, ExporterFactoryInterface $exporterFactory)
    {
        $v = new static($data);
        $v->addCustomRules();
        // name
        $v->rule('required', 'name');
        $v->rule('lengthMax', 'name', 200);

        // status
        $v->rule('required', 'status');
        $v->rule('in', 'status', array(0, 1));

        // description
        $v->rule('lengthMax', 'description', 65535);

        $exporter_names = $exporterFactory->getExporterNames();
        // exporter
        $v->rule('required', 'exporter');
        $v->rule('in', 'exporter', array_keys($exporter_names))
            ->message('Type is required.');

        // columns
        $v->rule('required', 'columns')
            ->message('Add at least one column.');

        // depending on the column we check if all are valid exporters
        if (isset($data['exporter']) &&
            in_array($data['exporter'], array_keys($exporter_names)) && isset($data['columns'])
        ) {

            /** @var ExporterInterface $exporter */
            $exporter = $exporterFactory->all()->getByIndex($data['exporter']);

            // columns

            $v->rule('allColumnsIn', 'columns', array_keys($exporter->getColumns()));
        }

        return $v;
    }
}
