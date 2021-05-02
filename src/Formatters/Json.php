<?php

namespace Differ\Formatters\Json;

use Exception;

/**
 * @param array $diffTree
 * @return string
 * @throws Exception
 */
function getJson(array $diffTree): string
{
    $result = json_encode((object) $diffTree);
    if (is_string($result)) {
        return $result;
    } else {
        throw new Exception('Incorrect Json encoding.');
    }
}
