<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parse(string $extension, string $content): array
{
    switch ($extension) {
        case 'json':
            return json_decode($content, true);
//            return json_decode($content);
        case 'yaml':
        case 'yml':
            return Yaml::parse($content);
//            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new Exception("Unknown extension \"$extension\"");
    }
}
