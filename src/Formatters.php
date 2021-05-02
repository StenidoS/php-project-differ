<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Stylish\getStylish;
use function Differ\Formatters\Plain\getPlain;
use function Differ\Formatters\Json\getJson;

/**
 * @param array $diffTree
 * @param string $formatType
 * @return string
 * @throws Exception
 */
function format(array $diffTree, string $formatType): string
{
    switch ($formatType) {
        case 'stylish':
            return getStylish($diffTree);
        case 'plain':
            return getPlain($diffTree);
        case 'json':
            return getJson($diffTree);
        default:
            throw new Exception("Unknown format \"$formatType\"");
    }
}
