<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Functional\sort;

/**
 * @param string $pathToFirstFile
 * @param string $pathToSecondFile
 * @param string $formatType
 * @return string
 * @throws Exception
 */
function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $formatType = 'stylish'): string
{
    $structure1 = parse(getFileExtension($pathToFirstFile), getFileContent($pathToFirstFile));
    $structure2 = parse(getFileExtension($pathToSecondFile), getFileContent($pathToSecondFile));
    $diffTree = getDiffTree($structure1, $structure2);

    return format($diffTree, $formatType);
}

/**
 * @param object $structure1
 * @param object $structure2
 * @return array
 */
function getDiffTree(object $structure1, object $structure2): array
{
    $keys = array_keys(array_merge((array) $structure1, (array) $structure2));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    return array_map(
        function ($key) use ($structure1, $structure2) {
            $oldValue = $structure1->$key ?? null;
            $newValue = $structure2->$key ?? null;

            if (is_object($oldValue) && is_object($newValue)) {
                return [
                    'key' => $key,
                    'type' => 'parent',
                    'children' => getDiffTree($structure1->$key, $structure2->$key),
                ];
            }

            if (!property_exists($structure2, $key)) {
                $type = 'removed';
            } elseif (!property_exists($structure1, $key)) {
                $type = 'added';
            } elseif ($oldValue !== $newValue) {
                $type = 'modified';
            } else {
                $type = 'unmodified';
            }

            return [
                'key' => $key,
                'type' => $type,
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ];
        },
        $sortedKeys
    );
}

/**
 * @param string $path
 * @return string
 * @throws Exception
 */
function getFileExtension(string $path): string
{
    if (file_exists($path)) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
    } else {
        throw new Exception("File $path not exists.");
    }

    return $extension;
}

/**
 * @param string $path
 * @return string
 * @throws Exception
 */
function getFileContent(string $path): string
{
    if (is_readable($path)) {
        $fileData = file_get_contents($path);
    } else {
        throw new Exception("File $path not exists or not readable.");
    }

    if (is_string($fileData)) {
        return $fileData;
    } else {
        throw new Exception("File $path content is not in string format.");
    }
}
