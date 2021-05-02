<?php

namespace Differ\Formatters\Json;

/**
 * @param array $diffTree
 * @return string
 */
function getJson(array $diffTree): string
{
    return json_encode((object) $diffTree);
}
