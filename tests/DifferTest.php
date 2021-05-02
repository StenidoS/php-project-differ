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
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fileName;
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
                'before.plain.json',
                'after.plain.json',
            ],
            'flat yaml files -- stylish' => [
                'stylish.diff',
                'before.plain.yaml',
                'after.plain.yaml',
            ],
            'complex json files -- stylish' => [
                'stylish.complex.diff',
                'before.complex.json',
                'after.complex.json',
                'stylish'
            ],
            'complex yaml files -- stylish' => [
                'stylish.complex.diff',
                'before.complex.yaml',
                'after.complex.yaml',
                'stylish'
            ],
            'complex json files -- plain' => [
                'plain.complex.diff',
                'before.complex.json',
                'after.complex.json',
                'plain'
            ],
            'complex yaml files -- plain' => [
                'plain.complex.diff',
                'before.complex.yaml',
                'after.complex.yaml',
                'plain'
            ],
            'complex json files -- json' => [
                'json.complex.diff',
                'before.complex.json',
                'after.complex.json',
                'json'
            ],
            'complex yaml files -- json' => [
                'json.complex.diff',
                'before.complex.yaml',
                'after.complex.yaml',
                'json'
            ],
        ];
    }

    /**
     * @param string $beforeFile
     * @param string $afterFile
     * @param string $formatType
     * @param string $expected
     * @throws Exception
     * @dataProvider additionProvider
     */
    public function testGenDiff(string $expected, string $beforeFile, string $afterFile, string $formatType = 'stylish')
    {
        $beforeFile = $this->getFixturePath($beforeFile);
        $afterFile = $this->getFixturePath($afterFile);
        $expected = $this->getFixtureContent($expected);

        $this->assertEquals($expected, genDiff($beforeFile, $afterFile, $formatType));
    }
}
