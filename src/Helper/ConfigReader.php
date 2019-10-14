<?php

namespace Rashidul\Chamomile\Helper;


class ConfigReader
{

    private $config;

    /**
     * ConfigReader constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getValidationRules($item = null)
    {

        $fields = $this->getFields();

        $rules = [];

        foreach ($fields as $field_name => $options){
            if (array_key_exists('validations', $options) && $options['validations'] != ''){

                $rule = $options['validations'];
                if ( str_contains($options['validations'], '{id}') ){
                    $replacer = $item ? ',' . $item->getKey() : '';
                    $rule = str_replace('{id}', $replacer, $rule);

                }
                $rules[$field_name] = $rule;


            }
        }

        return $rules;
    }

    public function getFieldsWithLabels()
    {

        $fields = $this->getFields();

        $data = [];

        foreach ($fields as $field => $attributes) {
            $data[$field] = $attributes['label'];
        }

        return $data;
    }

    public function getFields()
    {
        return $this->config['fields'];
    }

    public function getEntityName()
    {
        return $this->config['name'];
    }

}