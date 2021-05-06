<?php

namespace Differ\Formatters;

use Exception;

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
            return Stylish\format($diffTree);
        case 'plain':
            return Plain\format($diffTree);
        case 'json':
            return Json\format($diffTree);
        default:
            throw new Exception("Unknown format \"$formatType\"");
    }
}
