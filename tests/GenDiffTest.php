<?php
namespace GenerateDiff\Tests;

use \PHPUnit\Framework\TestCase;
use function \GenerateDiff\genDiff;

class GenDiffTest extends TestCase
{
    const FIXTURES_DIR = 'tests'  . DIRECTORY_SEPARATOR . 'fixtures';
    const EXPECTED = <<<EXPECTED
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
EXPECTED;
    private function getPath($name)
    {
        return self::FIXTURES_DIR . DIRECTORY_SEPARATOR . $name;
    }
    public function testJson()
    {
        $this->assertEquals(
            self::EXPECTED,
            genDiff($this->getPath('before.json'), $this->getPath('after.json'))
        );
    }
    public function testYaml()
    {
        $this->assertEquals(
            self::EXPECTED,
            genDiff($this->getPath('before.yaml'), $this->getPath('after.yaml'))
        );
    }
}
