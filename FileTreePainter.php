<?php


function getFilesAndDirectories($dirName)
{
    return scandir($dirName);
}

function paintDirectoriesAndFiles(array $filesAndDirectories, &$level, $realDirName, $delimiter)
{
    foreach ($filesAndDirectories as $oneItem){
        if ($oneItem === "." || $oneItem === "..")
            continue;
        $name = $realDirName.'\\'.$oneItem;
        $permission = substr(sprintf('%o', fileperms($name)), -4);
        if ($permission[3] < 4)
            continue;
        print(str_repeat("-", $level));
        if (is_dir($name)){
            printf('%s', $name.$delimiter);
            $level += 2;
            $dirName = $name;
            $newFiles = getFilesAndDirectories($dirName);
            paintDirectoriesAndFiles($newFiles, $level, $dirName, $delimiter);
        }
        if (is_file($name)){
            printf('%s', $realDirName.'\\'.$oneItem.$delimiter);
        }
    }
    $level -= 2;
}

function startPaint($delimiter)
{
    printf('Script started from: %s', dirname(dirname(__FILE__)).$delimiter);
    print("File tree:".$delimiter);
    $filesAndDirectories = getFilesAndDirectories(dirname(__FILE__));
    $level = 0;
    paintDirectoriesAndFiles($filesAndDirectories, $level, dirname(__FILE__), $delimiter);
}

global $delimiter;
$sapi = php_sapi_name();
if ($sapi=='cli')
    $delimiter = "\r\n";
else
    $delimiter = "<br>";
startPaint($delimiter);