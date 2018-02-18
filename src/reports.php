<?php
namespace GenerateDiff\Reports;

use Funct\Collection;
use function GenerateDiff\Library\getIndent;

function report($format, $arr)
{
    switch ($format) {
        case 'pretty':
            return prettyReport($arr);
        case 'json':
            return jsonReport($arr);
        case 'plain':
            return plainReport($arr);
        default:
            throw new \Exception('You can choose only \'plain\', \'json\' or \'pretty\' report format!');
    }
}

function jsonReport($arr)
{
    return json_encode($arr, JSON_PRETTY_PRINT);
}

function plainReport($arr)
{
    $iter = function ($arr, $fullName) use (&$iter) {
        return array_reduce($arr, function ($acc, $item) use ($iter, $fullName) {
            $fullName[] = $item['key'];
            $property = implode('.', $fullName);
            switch ($item['type']) {
                case 'nested':
                    $acc = array_merge($acc, $iter($item['children'], $fullName));
                    break;
                case 'added':
                    $result = is_array($item['value']) ? 'complex value' : $item['value'];
                    $acc[] = "Property '{$property}' was added with value: '{$result}'";
                    break;
                case 'removed':
                    $acc[] = "Property '{$property}' was removed";
                    break;
                case 'changed':
                    $from = is_array($item['before']) ? 'complex value' : $item['before'];
                    $to = is_array($item['after']) ? 'complex value' : $item['after'];
                    $acc[] = "Property '{$property}' was changed. From '{$from}' to '{$to}'";
                    break;
            }
            return $acc;
        }, []);
    };
    return implode(PHP_EOL, $iter($arr, []));
}

function prettyReport($arr)
{
    $iter = function ($arr, $level) use (&$iter) {
        return array_map(function ($item) use ($level, $iter) {
            switch ($item['type']) {
                case 'nested':
                    return [
                        getIndent($level) . "  {$item['key']}: {",
                        $iter($item['children'], $level + 1),
                        getIndent($level) . "  }"
                      ];
                case 'unchanged':
                    return getOutputString($level, $item['key'], $item['value']);
                case 'added':
                    return getOutputString($level, $item['key'], $item['value'], '+');
                break;
                case 'removed':
                    return getOutputString($level, $item['key'], $item['value'], '-');
                break;
                case 'changed':
                    $result[] = getOutputString($level, $item['key'], $item['after'], '+');
                    $result[] = getOutputString($level, $item['key'], $item['before'], '-');
                    return $result;
                default:
                    return;
            }
        }, $arr);
    };
    return implode(
        PHP_EOL,
        array_merge(
            ['{'],
            Collection\flattenAll($iter($arr, 0)),
            ['}']
        )
    );
}

function getOutputString($level, $key, $value, $prefix = ' ')
{
    if (is_array($value)) {
        $map = array_map(function ($key) use ($value, $level) {
            return getOutputString($level + 1, $key, $value[$key]);
        }, array_keys($value));
        return
          [
              getIndent($level) . $prefix .
              " {$key}: {",
              $map,  getIndent($level) . "  }"
          ];
    }
    return implode(
        [
            getIndent($level),
            $prefix,
            " {$key}: ",
            $value
        ]
    );
}
