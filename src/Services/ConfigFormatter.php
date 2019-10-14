<?php

namespace Rashidul\Chamomile\Services;


class ConfigFormatter
{

    public static function format(array $config) : array
    {
        $fields = static::formatFields($config);
    }

    private static function formatFields(array $config)
    {
        $fields = [];
        foreach ($config['fields'] as $name => $meta){
            dd($name);
            dd($meta);
        }
    }
}