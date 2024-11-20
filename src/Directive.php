<?php

namespace Stri8ed\LaravelConfigJs;

use Illuminate\Support\Arr;

class Directive
{
    public static function compile(string $expression): string
    {
        if (str_starts_with($expression, '[')) {
            $expression = trim(trim($expression, "'\""), '[]');
            $keys = array_map(function ($key) {
                return trim(trim($key), "'\"");
            }, explode(',', $expression));
        } else {
            $keys = [trim($expression, "'\"")];
        }

        $config = [];
        foreach ($keys as $key) {
            Arr::set($config, $key, config($key));
        }

        $functionName = config('config-js.function_name', 'laravelConfig');

        return '<script type="text/javascript">window.' . $functionName . ' = (key, defaultVal = null) => 
            key.split(".").reduce((obj, part) => obj?.[part], ' . json_encode($config) . ') ?? defaultVal;
        </script>';
    }
}