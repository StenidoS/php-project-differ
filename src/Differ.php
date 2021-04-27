<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\parse;
use function Functional\sort;

function genDiff(string $beforeFile, $afterFile): string
{
    $beforeRawData = parse(getFileExtension($beforeFile), getFileContent($beforeFile));
    $afterRawData = parse(getFileExtension($afterFile), getFileContent($afterFile));

    $before = convertBoolValues($beforeRawData);
    $after = convertBoolValues($afterRawData);

    $keys = array_keys(array_merge($before, $after));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    $result = array_reduce(
        $sortedKeys,
        function ($acc, $key) use ($before, $after) {
            if (array_key_exists($key, $before) && array_key_exists($key, $after)) {
                if ($before[$key] === $after[$key]) {
                    $acc .= "    $key: $before[$key]\n";
                } else {
                    $acc .= "  - $key: $before[$key]\n";
                    $acc .= "  + $key: $after[$key]\n";
                }
            } elseif (array_key_exists($key, $before) && !array_key_exists($key, $after)) {
                $acc .= "  - $key: $before[$key]\n";
            } else {
                $acc .= "  + $key: $after[$key]\n";
            }

            return $acc;
        },
        ''
    );

    return "{\n$result}";
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

function convertBoolValueToString(bool $value): string
{
    return $value ? 'true' : 'false';
}

function convertBoolValues(array $array): array
{
    return array_map(fn($value) => is_bool($value) ? convertBoolValueToString($value) : $value, $array);
}
