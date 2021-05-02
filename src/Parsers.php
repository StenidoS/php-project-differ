<?php

namespace Differ\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @param string $extension
 * @param string $content
 * @return object
 * @throws Exception
 */
function parse(string $extension, string $content): object
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
