<?php

/**
 * get,put,delete and check sessions, as well as flashing sessions messages
 * @author KhalidElMasnaoui
 * @copyright (c)) 2023
 */
class session
{
    /**
     * Summary of put
     * @param string $name
     * @param string|array<string> $value
     * @param ?string $sub
     * @return string
     */
    public static function put(string $name, $value, $sub = null)
    {
        if ($sub == null) {
            return $_SESSION[$name] = $value;
        } else {
            return $_SESSION[$name][$sub] = $value;
        }
    }

    /**
     * Summary of exists
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return (isset($_SESSION[$name]) ? true : false);
    }

    /**
     * Summary of get
     * @param string $name
     * @return mixed
     */
    public static function get(string $name)
    {
        if (self::exists($name)) {
            return $_SESSION[$name];
        }
    }

    /**
     * Summary of delete
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Summary of flash
     * @param string $name
     * @param string|array $string
     * @return mixed
     */
    public static function flash(string $name, $string = [])
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }
}
