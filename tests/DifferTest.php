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

    public function additionProvider(): array
    {
        return [
            'flat json files' => ['before.json', 'after.json', 'result.diff'],
        ];
    }
}
