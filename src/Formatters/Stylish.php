<?php

namespace Differ\Formatters\Stylish;

use Exception;

/**
 * @param array $diffTree
 * @return string
 * @throws Exception
 */
function format(array $diffTree): string
{
    $result = makeStylish($diffTree);

    return "{\n" . $result . "\n}";
}

/**
 * @param array $diffTree
 * @param int $depth
 * @return string
 * @throws Exception
 */
function makeStylish(array $diffTree, int $depth = 1): string
{
    $result = array_map(
        function ($node) use ($depth): string {
            $type = $node['type'] ?? null;
            $key = $node['key'] ?? null;

            $indent = getIndent($depth);
            $smallIndent = getSmallIndent($depth);

            switch ($type) {
                case 'parent':
                    return "{$indent}$key: {\n" . makeStylish($node['children'], $depth + 1) . "\n$indent}";
                case 'unmodified':
                    $value = stylishNodeValue($node['oldValue'], $depth);

                    return "{$indent}$key: $value";
                case 'modified':
                    $oldValue = stylishNodeValue($node['oldValue'], $depth);
                    $newValue = stylishNodeValue($node['newValue'], $depth);

                    return "{$smallIndent}- $key: $oldValue\n"
                        . "{$smallIndent}+ $key: $newValue";
                case 'added':
                    $value = stylishNodeValue($node['newValue'], $depth);

                    return "{$smallIndent}+ $key: $value";
                case 'removed':
                    $value = stylishNodeValue($node['oldValue'], $depth);

                    return "{$smallIndent}- $key: $value";
                default:
                    throw new Exception("Unknown node type \"$type\".");
            }
        },
        $diffTree
    );

    return implode("\n", $result);
}

/**
 * @param mixed $value
 * @param int $depth
 * @return string
 */
function stylishNodeValue($value, int $depth): string
{
    if (!is_object($value)) {
        return toString($value);
    }

    $keys = array_keys(get_object_vars($value));
    $result = array_map(
        function ($key) use ($value, $depth): string {
            $indent = getIndent($depth + 1);

            return "{$indent}{$key}: " . stylishNodeValue($value->$key, $depth + 1);
        },
        $keys
    );
    $endIndent = getIndent($depth);

    return "{\n" . implode("\n", $result) . "\n{$endIndent}}";
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

    return $value;
}

/**
 * @param int $depth
 * @return string
 */
function getSmallIndent(int $depth): string
{
    return getIndent($depth, 2);
}

/**
 * @param int $depth
 * @param int $shift
 * @return string
 */
function getIndent(int $depth = 1, int $shift = 0): string
{
    $baseIndentSize = 4;

    return str_repeat(' ', $baseIndentSize * $depth - $shift);
}
