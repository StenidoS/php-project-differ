<?php

namespace Differ\Formatters\Json;

use Exception;

/**
 * @param array $diffTree
 * @return string
 * @throws Exception
 */
function format(array $diffTree): string
{
    $result = json_encode($diffTree);
    if (is_string($result)) {
        return $result;
    } else {
        throw new Exception("Impossible to encode data into JSON.");
    }
}
