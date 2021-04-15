<?php

namespace Differ\Differ;

use function Functional\sort;

function genDiff(string $firstFile, $secondFile): string
{
    $firstFileData = decodeFileData(getFileData($firstFile));
    $secondFileData = decodeFileData(getFileData($secondFile));
    $firstData = convertBoolValues($firstFileData);
    $secondData = convertBoolValues($secondFileData);
    $keys = array_keys(array_merge($firstData, $secondData));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    $result = array_reduce(
        $sortedKeys,
        function ($acc, $key) use ($firstData, $secondData) {
            if (array_key_exists($key, $firstData) && array_key_exists($key, $secondData)) {
                if ($firstData[$key] === $secondData[$key]) {
                    $acc .= "    $key: $firstData[$key]\n";
                } else {
                    $acc .= "  - $key: $firstData[$key]\n";
                    $acc .= "  + $key: $secondData[$key]\n";
                }
            } elseif (array_key_exists($key, $firstData) && !array_key_exists($key, $secondData)) {
                $acc .= "  - $key: $firstData[$key]\n";
            } else {
                $acc .= "  + $key: $secondData[$key]\n";
            }

            return $acc;
        },
        ''
    );

    return "{\n$result}\n";
}

function getFileData(string $path)
{
    if (file_exists($path) && is_readable($path)) {
        $fileData = file_get_contents($path);
    } else {
        throw new \Exception("File $path not exists or not readable");
    }

    return $fileData;
}

function decodeFileData(string $fileData)
{
    $jsonDecodedData = json_decode($fileData, true);

    $jsonError = json_last_error();
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("Invalid json data. $jsonError}");
    }

    return $jsonDecodedData;
}

function bool2string(bool $value): string
{
    return $value ? 'true' : 'false';
}

function convertBoolValues(array $array): array
{
    return array_map(fn($item) => is_bool($item) ? bool2string($item) : $item, $array);
}
