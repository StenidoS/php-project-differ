<?php

namespace Differ\Formatters\Plain;

use Exception;

use function Functional\flat_map;

/**
 * @param array $diffTree
 * @return string
 * @throws Exception
 */
function getPlain(array $diffTree): string
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
            $value = $node['value'] ?? null;
            $oldValue = $node['oldValue'] ?? null;
            $newValue = $node['newValue'] ?? null;

            if (is_object($value)) {
                $value = '[complex value]';
            } else {
                $value = toString($value);
            }
            if (is_object($oldValue)) {
                $oldValue = '[complex value]';
            } else {
                $oldValue = toString($oldValue);
            }
            if (is_object($newValue)) {
                $newValue = '[complex value]';
            } else {
                $newValue = toString($newValue);
            }

            switch ($type) {
                case 'parent':
                    return makePlain($node['children'], "{$parentKey}{$key}.");
                case 'unmodified':
                    return '';
                case 'modified':
                    return "Property '{$parentKey}{$key}' was updated. From $oldValue to $newValue";
                case 'added':
                    return "Property '{$parentKey}{$key}' was added with value: $value";
                case 'removed':
                    return "Property '{$parentKey}{$key}' was removed";
                default:
                    throw new Exception('Unknown node type.');
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

    return $value;
}
