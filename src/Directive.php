<?php

namespace Stri8ed\LaravelConfigJs;

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
            self::setNestedValue($config, $key, config($key));
        }

        $functionName = config('config-js.function_name', 'laravelConfig');

        return '<script type="text/javascript">window.' . $functionName . ' = (key, defaultVal = null) => 
            key.split(".").reduce((obj, part) => obj?.[part], ' . json_encode($config) . ') ?? defaultVal;
        </script>';
    }

    /**
     * Set a nested value in an array using dot notation.
     *
     * @param array &$array The array to modify
     * @param string $key The dot notation key
     * @param mixed $value The value to set
     */
    private static function setNestedValue(array &$array, string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $segment) {
            if (!isset($current[$segment])) {
                $current[$segment] = [];
            }
            $current = &$current[$segment];
        }

        $current = $value;
    }
}