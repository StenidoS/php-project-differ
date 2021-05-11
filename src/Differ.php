<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Functional\sort;

/**
 * @param string $beforeFile
 * @param string $afterFile
 * @param string $formatType
 * @return string
 * @throws Exception
 */
function genDiff(string $beforeFile, string $afterFile, string $formatType = 'stylish'): string
{
    $beforeData = parse(getFileExtension($beforeFile), getFileContent($beforeFile));
    $afterData = parse(getFileExtension($afterFile), getFileContent($afterFile));
    $diffTree = getDiffTree($beforeData, $afterData);

    return format($diffTree, $formatType);
}

/**
 * @param object $beforeData
 * @param object $afterData
 * @return array
 */
function getDiffTree(object $beforeData, object $afterData): array
{
    $keys = array_keys(array_merge((array) $beforeData, (array) $afterData));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    return array_map(
        function ($key) use ($beforeData, $afterData) {
            $oldValue = $beforeData->$key ?? null;
            $newValue = $afterData->$key ?? null;

            if (is_object($oldValue) && is_object($newValue)) {
                return [
                    'key' => $key,
                    'type' => 'parent',
                    'children' => getDiffTree($beforeData->$key, $afterData->$key),
                ];
            }

            if (!property_exists($afterData, $key)) {
                $type = 'removed';
            } elseif (!property_exists($beforeData, $key)) {
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
