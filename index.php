<?php
// include_once __DIR__."/core/autoloader.php";

spl_autoload_register("autoload_api");

function autoload_api($class_name)
{ //Casino_RequestCasino--->

    $path = __DIR__ . "/classes/";
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
}
//get request's uri
// $uri = htmlspecialchars($_SERVER["REQUEST_URI"],ENT_QUOTES,"UTF-8"); 
$uri = strtok($_SERVER["REQUEST_URI"], '?');

//initiate db instance
$db = DB::getInstance();

switch ($uri) {
    case '/':
        // include('./pages/dashboard.php');
        header('Location: /pages/dashboard.php');

        break;

    case '/index.php':
        // include('./pages/dashboard.php');   
        header('Location: /pages/dashboard.php');

        break;

    default:
        //page not found -> 404 error page
        include('./pages/errors/404.php');



}
