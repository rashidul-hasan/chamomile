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
        foreach ($config['fields'] as $name => $meta){
            $meta['name'] = $name;
            array_push($fields, $meta);
        }
        return $fields;
    }
}