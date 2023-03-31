<?php

/**
 * Config Class
 * 
 * Get Configurations data.
 * 
 * @author khalid
 */
class config
{
    /**
     * get config key/value
     *
     * @param  string $path
     * @return string|bool|array
     */
    public static function get($path = null)
    {
        if ($path) {
            $config = $GLOBALS["config"];
            $path = explode("/", $path);
            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}


?>
