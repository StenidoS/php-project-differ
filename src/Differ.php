<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\parse;
use function Functional\sort;

function genDiff(string $beforeFile, $afterFile): string
{
    $beforeData = parse(getFileExtension($beforeFile), getFileContent($beforeFile));
    $afterData = parse(getFileExtension($afterFile), getFileContent($afterFile));
    $diffTree = getDiffTree($beforeData, $afterData);
    $result = stylish($diffTree);

    return "{\n$result\n}";
}

function getDiffTree(object $beforeData, object $afterData): array
{
    $keys = array_keys(array_merge((array) $beforeData, (array) $afterData));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    return array_map(
        function ($key) use ($beforeData, $afterData) {
            if (property_exists($beforeData, $key) && property_exists($afterData, $key)) {
                if (is_object($beforeData->$key) && is_object($afterData->$key)) {
                    return [
                        'key' => $key,
                        'type' => 'parent',
                        'children' => getDiffTree($beforeData->$key, $afterData->$key)
                    ];
                }
                if ($beforeData->$key === $afterData->$key) {
                    return [
                        'key' => $key,
                        'type' => 'unmodified',
                        'value' => $beforeData->$key,
                    ];
                } else {
                    return [
                        'key' => $key,
                        'type' => 'modified',
                        'oldValue' => $beforeData->$key,
                        'newValue' => $afterData->$key
                    ];
                }
            }
            if (property_exists($beforeData, $key) && !property_exists($afterData, $key)) {
                return [
                    'key' => $key,
                    'type' => 'removed',
                    'value' => $beforeData->$key,
                ];
            } else {
                return [
                    'key' => $key,
                    'type' => 'added',
                    'value' => $afterData->$key
                ];
            }
        },
        $sortedKeys
    );
}

function getFileExtension(string $path): string
{
    if (file_exists($path)) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
    } else {
        throw new Exception("File $path not exists.");
    }

    return $extension;
}

function getFileContent(string $path): string
{
    if (file_exists($path) && is_readable($path)) {
        $fileData = file_get_contents($path);
    } else {
        throw new Exception("File $path not exists or not readable.");
    }

    return $fileData;
}

function stylish(array $diffTree, $indent = 0): string
{
    $indentMap = [
        'parent' => 4 + $indent,
        'unmodified' => 4 + $indent,
        'modified' => 2 + $indent,
        'added' => 2 + $indent,
        'removed' => 2 + $indent
    ];

    $result = array_map(
        function ($node) use ($indentMap) {
            $type = $node['type'] ?? null;
            $key = $node['key'] ?? null;
            $value = $node['value'] ?? null;
            $oldValue = $node['oldValue'] ?? null;
            $newValue = $node['newValue'] ?? null;

            $countedIndent = str_repeat(' ', $indentMap[$type]);
            $countedEndIndent = str_repeat(' ', $indentMap[$type] + 2);

            if (is_object($value)) {
                $value = styleInnerObject($value, $indentMap[$type], $countedEndIndent);
            } else {
                $value = toString($value);
            }
            if (is_object($oldValue)) {
                $oldValue = styleInnerObject($oldValue, $indentMap[$type], $countedEndIndent);
            } else {
                $oldValue = toString($oldValue);
            }
            if (is_object($newValue)) {
                $newValue = styleInnerObject($newValue, $indentMap[$type], $countedEndIndent);
            } else {
                $newValue = toString($newValue);
            }

            switch ($type) {
                case 'parent':
                    return "{$countedIndent}$key: {\n" . stylish($node['children'], $indentMap[$type])
                         . "\n{$countedIndent}}";
                case 'unmodified':
                    return "{$countedIndent}$key: $value";
                case 'modified':
                    return "{$countedIndent}- $key: $oldValue\n"
                         . "{$countedIndent}+ $key: $newValue";
                case 'added':
                    return "{$countedIndent}+ $key: $value";
                case 'removed':
                    return "{$countedIndent}- $key: $value";
                default:
                    throw new Exception('Unknown node type.');
            }
        },
        $diffTree
    );

    return implode("\n", $result);
}

function styleInnerObject(object $object, int $indentStart, string $indentEnd): string
{
    return "{\n" . stylish(processInnerObject($object), $indentStart + 2) . "\n{$indentEnd}}";
}

function toString($value): string // TODO type hint
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return $value;
}

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
