<?php
namespace GenerateDiff;

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
    echo genDiff($args['<firstFile>'], $args['<secondFile>']);
}

function genDiff($pathToFile1, $pathToFile2)
{
    $part1 = $part2_minus = $part2_plus = $part3 = $part4 = [];

    $arr1 = jsonToArr($pathToFile1);
    $arr2 = jsonToArr($pathToFile2);

    foreach ($arr1 as $key => $value) {
        if (!array_key_exists($key, $arr2)) {
            $part3[$key] = $value;
        } else {
            if ($arr2[$key] === $value) {
                $part1[$key] = $value;
            } else {
                $part2_plus[$key] = $arr2[$key];
                $part2_minus[$key] = $arr1[$key];
            }
        }
    }
    $part4 = array_diff_key($arr2, $arr1);
    $str1 = arrayToStr($part1);
    $str2 = arrayToStr($part2_minus, ' ', $part2_plus);
    $str3 = arrayToStr($part3, '  - ');
    $str4 = arrayToStr($part4, '  + ');
    $result = "{" . PHP_EOL . $str1 . $str2 . $str3 . $str4 . "}" . PHP_EOL;
    return $result;
}

function jsonToArr($path)
{
    $content = file_get_contents($path);
    $arr = json_decode($content, true);
    return array_map(
        function ($i) {
            return boolToString($i);
        },
        $arr
    );
}

function arrayToStr($arr1, $prefix = '    ', $arr2 = null)
{
    $str = '';
    if ($arr2) {
        foreach ($arr2 as $key => $value) {
            $str .= "  + " . $key . ": " . $arr2[$key] .PHP_EOL . "  - " .
            $key . ": " . $arr1[$key] . PHP_EOL;
        }
    } else {
        foreach ($arr1 as $key => $value) {
            $str .= $prefix . $key . ": " . $arr1[$key] . PHP_EOL;
        }
    }
    return $str;
}
function boolToString($item)
{
    if (is_bool($item)) {
        switch ($item) {
            case true:
                return 'true';
            case false:
                return 'false';
        }
    }
    // return "\"{$variable}\"";
    return $item;
}
