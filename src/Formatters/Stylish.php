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
            $smallIndent = getIndent($depth, true);

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
 * @throws Exception
 */
function stylishNodeValue($value, int $depth): string
{
    if (is_array($value)) {
        $indent = getIndent($depth);

        return "{\n" . makeStylish($value, $depth + 1) . "\n$indent}";
    }

    return toString($value);
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
 * @param bool $small
 * @return string
 */
function getIndent(int $depth = 1, bool $small = false): string
{
    $baseIndentSize = 4;
    $times = $baseIndentSize * $depth;

    if ($small) {
        $times -= 2;
    }

    return str_repeat(' ', $times);
}
