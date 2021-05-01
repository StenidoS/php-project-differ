<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\Differ\processInnerObject;

function getStylish($diffTree)
{
    $result = makeStylish($diffTree);

    return "{\n" . $result . "\n}";
}

function makeStylish(array $diffTree, $indent = 0): string
{
    $indentMap = [
        'parent' => 4 + $indent,
        'unmodified' => 4 + $indent,
        'modified' => 2 + $indent,
        'added' => 2 + $indent,
        'removed' => 2 + $indent,
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
                $value = stylishInnerObject($value, $indentMap[$type], $countedEndIndent);
            } else {
                $value = toString($value);
            }
            if (is_object($oldValue)) {
                $oldValue = stylishInnerObject($oldValue, $indentMap[$type], $countedEndIndent);
            } else {
                $oldValue = toString($oldValue);
            }
            if (is_object($newValue)) {
                $newValue = stylishInnerObject($newValue, $indentMap[$type], $countedEndIndent);
            } else {
                $newValue = toString($newValue);
            }

            switch ($type) {
                case 'parent':
                    return "{$countedIndent}$key: {\n" . makeStylish($node['children'], $indentMap[$type])
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

function stylishInnerObject(object $object, int $indentStart, string $indentEnd): string
{
    return "{\n" . makeStylish(processInnerObject($object), $indentStart + 2) . "\n{$indentEnd}}";
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
