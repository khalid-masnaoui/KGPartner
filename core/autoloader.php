<?php

/**
 * Summary of autoload_api
 * @param string $class_name
 * @return bool
 */
function autoload_api($class_name)
{ //Casino_RequestCasino--->

    $path = __DIR__ . "/../classes/";
    $extension = ".php";

    $pos = strpos($class_name, "_");

    if ($pos === false) {
        $full_Path = $path . $class_name . $extension;
    } else {
        $file_name = str_replace("_", DIRECTORY_SEPARATOR, $class_name);
        $full_Path = $path . $file_name . $extension;
    }

    if (!file_exists($full_Path)) {
        return false;
    }

    require_once $full_Path;
    return true;
}
//autoload classes
// spl_autoload_register("autoload_api");
