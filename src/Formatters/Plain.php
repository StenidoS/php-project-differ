<?php

namespace Differ\Formatters\Plain;

use Exception;

use function Functional\flat_map;

/**
 * @param array $diffTree
 * @return string
 * @throws Exception
 */
function format(array $diffTree): string
{
    $result = array_filter(makePlain($diffTree));

    return implode("\n", $result);
}

/**
 * @param array $diffTree
 * @param string $parentKey
 * @return array
 * @throws Exception
 */
function makePlain(array $diffTree, string $parentKey = ''): array
{
    return flat_map(
        $diffTree,
        function ($node) use ($parentKey) {
            $type = $node['type'] ?? null;
            $key = $node['key'] ?? null;

            switch ($type) {
                case 'parent':
                    return makePlain($node['children'], "{$parentKey}{$key}.");
                case 'unmodified':
                    return '';
                case 'modified':
                    $oldValue = toString($node['oldValue']);
                    $newValue = toString($node['newValue']);
                    return "Property '{$parentKey}{$key}' was updated. From $oldValue to $newValue";
                case 'added':
                    $value = toString($node['newValue']);
                    return "Property '{$parentKey}{$key}' was added with value: $value";
                case 'removed':
                    return "Property '{$parentKey}{$key}' was removed";
                default:
                    throw new Exception("Unknown node type \"$type\".");
            }
        }
    );
}

/**
 * @param mixed $value
 * @return string
 */
function toString($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    if (is_numeric($value)) {
        return (string) $value;
    }

    return '[complex value]';
}
