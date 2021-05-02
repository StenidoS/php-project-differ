<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{

    public function getFixturePath(string $fileName): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fileName;
    }

    public function getFixtureContent(string $fileName): string
    {
        return file_get_contents($this->getFixturePath($fileName));
    }

    public function additionProvider(): array
    {
        return [
            'flat json files -- stylish' => [
                'before.plain.json',
                'after.plain.json',
                'stylish',
                'stylish.diff',
            ],
            'flat yaml files -- stylish' => [
                'before.plain.yaml',
                'after.plain.yaml',
                'stylish',
                'stylish.diff',
            ],
            'complex json files -- stylish' => [
                'before.complex.json',
                'after.complex.json',
                'stylish',
                'stylish.complex.diff',
            ],
            'complex yaml files -- stylish' => [
                'before.complex.yaml',
                'after.complex.yaml',
                'stylish',
                'stylish.complex.diff',
            ],
            'complex json files -- plain' => [
                'before.complex.json',
                'after.complex.json',
                'plain',
                'plain.complex.diff',
            ],
            'complex yaml files -- plain' => [
                'before.complex.yaml',
                'after.complex.yaml',
                'plain',
                'plain.complex.diff',
            ],
            'complex json files -- json' => [
                'before.complex.json',
                'after.complex.json',
                'json',
                'json.complex.diff',
            ],
            'complex yaml files -- json' => [
                'before.complex.yaml',
                'after.complex.yaml',
                'json',
                'json.complex.diff',
            ],
        ];
    }

    /**
     * @dataProvider additionProvider
     */
    public function testGenDiff($beforeFile, $afterFile, $formatType, $expected)
    {
        $beforeFile = $this->getFixturePath($beforeFile);
        $afterFile = $this->getFixturePath($afterFile);
        $expected = $this->getFixtureContent($expected);

        $this->assertEquals($expected, genDiff($beforeFile, $afterFile, $formatType));
    }
}
