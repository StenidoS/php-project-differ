<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\Differ\processInnerObject;

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
 * @param int $indent
 * @return string
 * @throws Exception
 */
function makeStylish(array $diffTree, int $indent = 0): string
{
    $indentMap = [
        'parent' => 4 + $indent,
        'unmodified' => 4 + $indent,
        'modified' => 2 + $indent,
        'added' => 2 + $indent,
        'removed' => 2 + $indent,
    ];

    $result = array_map(
        function ($node) use ($indentMap): string {
            $type = $node['type'] ?? null;
            $key = $node['key'] ?? null;

            $countedIndent = str_repeat(' ', $indentMap[$type]);
            $countedEndIndent = str_repeat(' ', $indentMap[$type] + 2);

            switch ($type) {
                case 'parent':
                    return "{$countedIndent}$key: {\n" . makeStylish($node['children'], $indentMap[$type])
                        . "\n{$countedIndent}}";
                case 'unmodified':
                    $value = stylishNodeValue($node['value'], $indentMap[$type], $countedEndIndent);

                    return "{$countedIndent}$key: $value";
                case 'modified':
                    $oldValue = stylishNodeValue($node['oldValue'], $indentMap[$type], $countedEndIndent);
                    $newValue = stylishNodeValue($node['newValue'], $indentMap[$type], $countedEndIndent);

                    return "{$countedIndent}- $key: $oldValue\n"
                        . "{$countedIndent}+ $key: $newValue";
                case 'added':
                    $value = stylishNodeValue($node['value'], $indentMap[$type], $countedEndIndent);

                    return "{$countedIndent}+ $key: $value";
                case 'removed':
                    $value = stylishNodeValue($node['value'], $indentMap[$type], $countedEndIndent);

                    return "{$countedIndent}- $key: $value";
                default:
                    throw new Exception('Unknown node type.');
            }
        },
        $diffTree
    );

    return implode("\n", $result);
}

/**
 * @param mixed $value
 * @param int $indentStart
 * @param string $indentEnd
 * @return string
 * @throws Exception
 */
function stylishNodeValue($value, int $indentStart = 0, string $indentEnd = ''): string
{
    if (is_object($value)) {
        return "{\n" . makeStylish(processInnerObject($value), $indentStart + 2) . "\n{$indentEnd}}";
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
