<?php

/**
 * Cookies class
 *
 * Get,Put,Delete and check cookies
 *
 * @author khalid
 */
class cookie
{
    /**
     * exists : check if cookei exists
     *
     * @param  string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return (isset($_COOKIE[$name]) ? true : false);
    }

    /**
     * get : get the cookie
     *
     * @param  string $name
     * @return string
     */
    public static function get(string $name): string
    {
        return $_COOKIE[$name];
    }

    /**
     * set cookies with httponly flag & secure flag
     *
     * @param  string $name
     * @param  string $value
     * @param  int $expiry
     * @return bool
     */
    public static function put(string $name, string $value, int $expiry): bool
    {
        if (setcookie($name, $value, time() + $expiry, "/", false, true, true)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete : delete he cookie
     *
     * @param  string $name
     * @return void
     */
    public static function delete(string $name): void
    {
        if (self::exists($name)) {
            self::put($name, "", time() - 1);
        }
    }
}
