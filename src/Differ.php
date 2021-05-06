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
            if (!property_exists($afterData, $key)) {
                return [
                    'key' => $key,
                    'type' => 'removed',
                    'value' => $beforeData->$key,
                ];
            }
            if (!property_exists($beforeData, $key)) {
                return [
                    'key' => $key,
                    'type' => 'added',
                    'value' => $afterData->$key,
                ];
            }
            if (is_object($beforeData->$key) && is_object($afterData->$key)) {
                return [
                    'key' => $key,
                    'type' => 'parent',
                    'children' => getDiffTree($beforeData->$key, $afterData->$key),
                ];
            }
            if ($beforeData->$key !== $afterData->$key) {
                return [
                    'key' => $key,
                    'type' => 'modified',
                    'oldValue' => $beforeData->$key,
                    'newValue' => $afterData->$key,
                ];
            }

            return [
                'key' => $key,
                'type' => 'unmodified',
                'value' => $beforeData->$key,
            ];
        },
        $sortedKeys
    );
}

/**
 * @param object $object
 * @return array
 */
function processInnerObject(object $object): array
{
    $key = array_keys(get_object_vars($object));

    return array_map(
        function ($key) use ($object) {
            if (is_object($object->$key)) {
                return [
                    'key' => $key,
                    'type' => 'parent',
                    'children' => processInnerObject($object->$key),
                ];
            }

            return [
                'key' => $key,
                'type' => 'unmodified',
                'value' => $object->$key,
            ];
        },
        $key
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
