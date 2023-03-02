<?php

spl_autoload_register('myAutoloader');

function myAutoloader($className)
{
    $folders = ['controllers', 'core', 'utils', 'models'];
    $trueFile = null;
    foreach ($folders as $folder) {
        $fileName = './app/' . $folder . '/' . strtolower($className) . '.php';
        if (file_exists($fileName)) {
            $trueFile = $fileName;
            break;
        }
    }
    if ($trueFile == null) {
        return false;
    }
    include_once $trueFile;
}