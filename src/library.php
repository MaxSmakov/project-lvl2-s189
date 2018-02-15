<?php
namespace GenerateDiff\Library;

use \Symfony\Component\Yaml\Yaml;

function getExtension($pathToFile)
{
    $info = new \SplFileInfo($pathToFile);
    return $info->getExtension();
}

function parseContent($content, $extension)
{
    switch ($extension) {
        case 'json':
            $arr = json_decode($content, true);
            break;
        case 'yaml':
            $arr = Yaml::parse($content, true);
            break;
        default:
            throw new \Exception("file extension '{$extension}' is unsupported");
    }
    return array_map(
        function ($i) {
            return boolToString($i);
        },
        $arr
    );
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
    return $item;
}
