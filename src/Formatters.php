<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Stylish\getStylish;
use function Differ\Formatters\Plain\getPlain;

function format(array $diffTree, string $formatType): string
{
    switch ($formatType) {
        case 'stylish':
            return getStylish($diffTree);
        case 'plain':
            return getPlain($diffTree);
        default:
            throw new Exception("Unknown format \"$formatType\"");
    }
}
