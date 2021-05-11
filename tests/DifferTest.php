<?php

namespace Differ\Tests;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{

    /**
     * @param string $fileName
     * @return string
     */
    public function getFixturePath(string $fileName): string
    {
        return __DIR__ . '/fixtures/' . $fileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getFixtureContent(string $fileName): string
    {
        return file_get_contents($this->getFixturePath($fileName));
    }

    /**
     * @return array
     */
    public function additionProvider(): array
    {
        return [
            'flat json files -- stylish' => [
                'stylish.diff',
                'file1.plain.json',
                'file2.plain.json',
            ],
            'flat yaml files -- stylish' => [
                'stylish.diff',
                'file1.plain.yaml',
                'file2.plain.yaml',
            ],
            'complex json files -- stylish' => [
                'stylish.complex.diff',
                'file1.complex.json',
                'file2.complex.json',
                'stylish'
            ],
            'complex yaml files -- stylish' => [
                'stylish.complex.diff',
                'file1.complex.yaml',
                'file2.complex.yaml',
                'stylish'
            ],
            'complex json files -- plain' => [
                'plain.complex.diff',
                'file1.complex.json',
                'file2.complex.json',
                'plain'
            ],
            'complex yaml files -- plain' => [
                'plain.complex.diff',
                'file1.complex.yaml',
                'file2.complex.yaml',
                'plain'
            ],
            'complex json files -- json' => [
                'json.complex.diff',
                'file1.complex.json',
                'file2.complex.json',
                'json'
            ],
            'complex yaml files -- json' => [
                'json.complex.diff',
                'file1.complex.yaml',
                'file2.complex.yaml',
                'json'
            ],
        ];
    }

    /**
     * @param string $expected
     * @param string $pathToFirstFile
     * @param string $pathToSecondFile
     * @param string $formatType
     * @throws Exception
     * @dataProvider additionProvider
     */
    public function testGenDiff(
        string $expected,
        string $pathToFirstFile,
        string $pathToSecondFile,
        string $formatType = 'stylish'
    ) {
        $pathToFirstFile = $this->getFixturePath($pathToFirstFile);
        $pathToSecondFile = $this->getFixturePath($pathToSecondFile);
        $expected = $this->getFixtureContent($expected);

        $this->assertEquals($expected, genDiff($pathToFirstFile, $pathToSecondFile, $formatType));
    }
}
