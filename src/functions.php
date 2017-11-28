<?php

/**
 * Convert a given array to an associative array.
 * @param array $array
 * @return array
 */
function array_assoc (array $array) {
    return array_map_keys($array, function ($key, $value) {
        return [is_numeric($key) ? $value : $key => $value];
    });
}
