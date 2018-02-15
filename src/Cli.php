<?php
namespace GenerateDiff;

use function GenerateDiff\genDiff;

function run()
{
    $doc = <<<DOC

Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

DOC;

    $args = (new \Docopt\Handler)->handle($doc);
    echo genDiff($args['<firstFile>'], $args['<secondFile>']) . PHP_EOL;
}
