<?php

/**
 * Input Class
 * 
 * check and get data from http requests [GET,POST]
 * 
 * @author khalid
 */
class Input
{
    /**
     * exists we can replace this function by a simple if (isset($_post[submit]))    
     *
     * @param  string $type
     * @return bool
     */
    public static function exists(string $type = "post"): bool
    {
        switch ($type) {
            case "post":
                return (!empty($_POST) ? true : false);
                break;
            case "get":
                return (!empty($_GET) ? true : false);
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * get data
     * better to return the data after sanitizing it 
     *
     * @param  string $item
     * @return 
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        } else {
            return "";
        }
    }
}
