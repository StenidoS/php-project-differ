<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parse(string $extension, string $content)
{
    switch ($extension) {
        case 'json':
            return json_decode($content);
        case 'yaml':
        case 'yml':
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new Exception("Unknown extension \"$extension\"");
    }
}
