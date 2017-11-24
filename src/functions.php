<?php

/**
 * Map over an array with keys available for mutation.
 * The callable must return an array of [$key, $value].
 * @param array $array
 * @param callable $callable
 * @return array
 */
function array_map_with_keys (array $array, callable $callable)
{
    $mutated = [];

    foreach ($array as $key => $value) {
        list($key, $value) = $callable($key, $value);

        $mutated[$key] = $value;
    }

    return $mutated;
}
