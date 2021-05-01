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
            'flat json files' => ['before.plain.json', 'after.plain.json', 'plain.diff'],
            'flat yaml files' => ['before.plain.yaml', 'after.plain.yaml', 'plain.diff'],
            'complex json files' => ['before.complex.json', 'after.complex.json', 'complex.diff'],
            'complex yaml files' => ['before.complex.yaml', 'after.complex.yaml', 'complex.diff']
        ];
    }

    /**
     * @dataProvider additionProvider
     */
    public function testGenDiff($beforeFile, $afterFile, $expected)
    {
        $beforeFile = $this->getFixturePath($beforeFile);
        $afterFile = $this->getFixturePath($afterFile);
        $expected = $this->getFixtureContent($expected);

        $this->assertEquals($expected, genDiff($beforeFile, $afterFile));
    }
}
