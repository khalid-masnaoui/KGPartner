<?php

/**
 * Hashing Class
 * 
 * Hashing class using sha256 algorithm with salt
 * 
 * @author khalid
 */
class hash
{
    /**
     * make //its a one way : so when we want to check a password we should do the same algo with the same salt (thats why the salt is stored in the DB)    
     *
     * @param string $string
     * @param string $salt
     * @return string with 64char
     */
    public static function make(string $string, string $salt = ""): string
    {
        return hash("sha256", $string . $salt);
    }

    /**
     * salt : make a random salt
     *
     * @param  int $length
     * @return string
     */
    public static function salt(int $length): string
    {
        $salt = random_bytes($length);
        return bin2hex($salt);
    }

    /**
     * unique ,for the cookie_hash
     *
     * @return string
     */
    public static function unique()
    {
        return self::make(uniqid());
    }
}
