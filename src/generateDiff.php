<?php
namespace GenerateDiff;

use \Funct\Collection;

use function \GenerateDiff\Library\getExtension;
use function \GenerateDiff\Library\parseContent;

function genDiff($pathToFile1, $pathToFile2)
{
    $fileExtension1 = getExtension($pathToFile1);
    $fileExtension2 = getExtension($pathToFile2);
    $content1 = parseContent(file_get_contents($pathToFile1), $fileExtension1);
    $content2 = parseContent(file_get_contents($pathToFile2), $fileExtension2);

    return output(arrDiff($content1, $content2));
}

function arrDiff($array1, $array2)
{
    $union = Collection\union(array_keys($array1), array_keys($array2));

    return array_reduce($union, function ($acc, $key) use ($array1, $array2) {
        if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
            if ($array1[$key] === $array2[$key]) {
                $acc[] = [
                    'type' => 'unchanged',
                    'key' => $key,
                    'before' => $array2[$key],
                    'after' => $array2[$key]
                ];
            } else {
                $acc[] = [
                    'type' => 'changed',
                    'key' => $key,
                    'before' => $array1[$key],
                    'after' => $array2[$key]
                ];
            }
        } elseif (array_key_exists($key, $array1)) {
            $acc[] = [
                'type' => 'removed',
                'key' => $key,
                'before' => $array1[$key],
                'after' => ''
            ];
        } else {
            $acc[] = [
                'type' => 'added',
                'key' => $key,
                'before' => '',
                'after' => $array2[$key]
            ];
        }
        return $acc;
    }, []);
}

function output($arr)
{
    $newArr = array_map(function ($item) {
        switch ($item['type']) {
            case 'unchanged':
                return getOutputString($item['key'], $item['after']);
            case 'added':
                return getOutputString($item['key'], $item['after'], '+');
              break;
            case 'removed':
                return getOutputString($item['key'], $item['before'], '-');
              break;
            case 'changed':
                $result[] = getOutputString($item['key'], $item['after'], '+');
                $result[] = getOutputString($item['key'], $item['before'], '-');
                return $result;
            default:
                return '';
        }
    }, $arr);
    return implode(
        "\n",
        array_merge(
            ['{'],
            Collection\flattenAll($newArr),
            ['}']
        )
    );
}

function getOutputString($key, $value, $prefix = ' ')
{
    return '  ' . implode(
        [
            $prefix,
            " {$key}: ",
            $value
        ]
    );
}
