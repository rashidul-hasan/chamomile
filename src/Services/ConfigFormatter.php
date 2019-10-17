<?php

namespace Rashidul\Chamomile\Services;


class ConfigFormatter
{

    public static function format(array $config) : array
    {
        $fields = static::formatFields($config);
        $config['fields'] = $fields;
        return $config;
    }

    private static function formatFields(array $config)
    {
        $fields = [];

        $allFields = [];
        $indexFields = [];
        $createFields = [];
        $editFields = [];

        foreach ($config['fields'] as $name => $meta){
            $meta['name'] = $name;
            array_push($allFields, $meta);

            if ($meta['index']){
                array_push($indexFields, $meta);
            }
        }
        $fields['all'] = $allFields;
        $fields['index'] = $indexFields;
        return $fields;
    }
}