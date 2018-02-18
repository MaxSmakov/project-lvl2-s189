<?php
namespace GenerateDiff;

use Funct\Collection;

use function GenerateDiff\Library\getExtension;
use function GenerateDiff\Library\parseContent;
use function GenerateDiff\Reports\report;

function genDiff($format, $pathToFile1, $pathToFile2)
{
    try {
        $fileExtension1 = getExtension($pathToFile1);
        $fileExtension2 = getExtension($pathToFile2);
        $content1 = parseContent(file_get_contents($pathToFile1), $fileExtension1);
        $content2 = parseContent(file_get_contents($pathToFile2), $fileExtension2);
        return report($format, arrDiff($content1, $content2));
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }
}

function arrDiff($array1, $array2)
{
    $union = Collection\union(array_keys($array1), array_keys($array2));

    return array_reduce($union, function ($acc, $key) use ($array1, $array2) {
        if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
            if (is_array($array1[$key]) && is_array($array2[$key])) {
                $acc[] = [
                    'type' => 'nested',
                    'key' => $key,
                    'children' => arrDiff($array1[$key], $array2[$key])
                ];
            } else {
                if ($array1[$key] === $array2[$key]) {
                    $acc[] = [
                        'type' => 'unchanged',
                        'key' => $key,
                        'value' => $array2[$key]
                    ];
                } else {
                    $acc[] = [
                        'type' => 'changed',
                        'key' => $key,
                        'before' => $array1[$key],
                        'after' => $array2[$key]
                    ];
                }
            }
        } elseif (array_key_exists($key, $array1)) {
            $acc[] = [
                'type' => 'removed',
                'key' => $key,
                'value' => $array1[$key]
            ];
        } else {
            $acc[] = [
                'type' => 'added',
                'key' => $key,
                'value' => $array2[$key]
            ];
        }
        return $acc;
    }, []);
}
