<?php
namespace GenerateDiff\Tests;

use \PHPUnit\Framework\TestCase;
use function \GenerateDiff\genDiff;

class GenDiffTest extends TestCase
{
    const FIXTURES_DIR = 'tests'  . DIRECTORY_SEPARATOR . 'fixtures';
    const EXPECTED_FLAT = <<<EXPECTED
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
EXPECTED;
    const EXPECTED_TREE = <<<EXPECTED
{
    host: hexlet.io
  + timeout: 50
  - timeout: 10
  - get: true
    settings: {
    + timeout: 20
    - timeout: 290
    + speed: medium
    - speed: slow
      standBy: {
      + drums: pearl
      - drums: gretch
      + sticks: tama
      - sticks: vic firth
      - cymbals: zildjian
      }
    }
  + set: false
}
EXPECTED;
    private function getPath($name)
    {
        return self::FIXTURES_DIR . DIRECTORY_SEPARATOR . $name;
    }
    public function testJsonFlat()
    {
        $this->assertEquals(
            self::EXPECTED_FLAT,
            genDiff($this->getPath('before.json'), $this->getPath('after.json'))
        );
    }
    public function testYamlFlat()
    {
        $this->assertEquals(
            self::EXPECTED_FLAT,
            genDiff($this->getPath('before.yaml'), $this->getPath('after.yaml'))
        );
    }
    public function testJsonTree()
    {
        $this->assertEquals(
            self::EXPECTED_TREE,
            genDiff($this->getPath('tree-before.json'), $this->getPath('tree-after.json'))
        );
    }
    public function testYamlTree()
    {
        $this->assertEquals(
            self::EXPECTED_TREE,
            genDiff($this->getPath('tree-before.yaml'), $this->getPath('tree-after.yaml'))
        );
    }
}
