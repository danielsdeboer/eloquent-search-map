<?php

/**
 * Convert a given array to an associative array.
 * @param array $array
 * @return array
 */
function array_assoc (array $array)
{
    return array_map_keys($array, function ($key, $value) {
        return [is_numeric($key) ? $value : $key => $value];
    });
}

/**
 * @param string $haystack
 * @return \Closure
 */
function string_contains (string $haystack)
{
    return function (string $needle) use ($haystack) {
        return str_contains($haystack, $needle);
    };
}

/**
 * Convert dot notated strings to underscores.
 * @param string $dotted
 * @return mixed
 */
function dot_to_underscore (string $dotted)
{
    return str_replace('.', '_', $dotted);
}
