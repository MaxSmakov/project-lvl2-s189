<?php
namespace GenerateDiff\Tests;

use PHPUnit\Framework\TestCase;
use function GenerateDiff\genDiff;
use const GenerateDiff\Tests\EXPECTED_TREE;
use const GenerateDiff\Tests\EXPECTED_FLAT;
use const GenerateDiff\Tests\EXPECTED_PLAIN;


class GenDiffTest extends TestCase
{
    const FIXTURES_DIR = 'tests'  . DIRECTORY_SEPARATOR . 'fixtures';

    private function getPath($name)
    {
        return self::FIXTURES_DIR . DIRECTORY_SEPARATOR . $name;
    }

    public function testJsonFlat()
    {
        $this->assertEquals(
            EXPECTED_FLAT,
            genDiff('pretty', $this->getPath('before.json'), $this->getPath('after.json'))
        );
    }
    public function testYamlFlat()
    {
        $this->assertEquals(
            EXPECTED_FLAT,
            genDiff('pretty', $this->getPath('before.yaml'), $this->getPath('after.yaml'))
        );
    }
    public function testJsonTree()
    {
        $this->assertEquals(
            EXPECTED_TREE,
            genDiff('pretty', $this->getPath('tree-before.json'), $this->getPath('tree-after.json'))
        );
    }
    public function testYamlTree()
    {
        $this->assertEquals(
            EXPECTED_TREE,
            genDiff('pretty', $this->getPath('tree-before.yaml'), $this->getPath('tree-after.yaml'))
        );
    }
    public function testPlainReport()
    {
      $this->assertEquals(
            EXPECTED_PLAIN,
            genDiff('plain', $this->getPath('tree-before.json'), $this->getPath('tree-after.json'))
        );
    }
}
