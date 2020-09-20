<?php


namespace Jcc\Jwt\Support;


class Token
{
    static $token;

    public static function setToken($token)
    {
        static::$token = $token;
    }
    public static function getToken()
    {
        return static::$token??'';
    }

}
