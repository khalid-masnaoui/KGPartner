<?php

/**
 * Token
 * 
 * generate and check tokens -->[ Against CSRF] (bloquer les submits venant d'autres sources qu'à la formulaire (url...))
 * 
 * @author khalid
 */
class token
{
    /**
     * generate
     * @param  string $name
     * @return string
     */
    public static function generate($name = "token")
    {
        $name = ($name == "token") ? config::get("session/token_name") : $name;
        return session::put($name, md5(uniqid()));
    }

    /**
     * check
     *
     * @param  string $token
     * @param  string $name
     * @return bool
     */
    public static function check(string $token, $name = "token"): bool
    {
        $tokenName = ($name == "token") ? config::get("session/token_name") : $name;
        if (session::exists($tokenName) && $token == session::get($tokenName)) { //use also time_delai_ for the session (time()-session[]<=delai)
            session::delete($tokenName);
            return true;
        } else {
            return false;
        }
    }
}
