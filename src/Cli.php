<?php
namespace GenerateDiff\Cli;

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

$handler = new \Docopt\Handler(array(
        'help'=>true,
        'optionsFirst'=>false
));
$handler->handle($doc);
}
