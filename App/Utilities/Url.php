<?php 
namespace App\Utilities;
class Url
{
    public static function current(bool $params = true):string
    {
        return (!$params) ? self::base() : self::base() . $_SERVER["REQUEST_URI"];
    }
    public static function base():string
    {
        return(isset($_SERVER['HTTPS']) && 
        $_SERVER['HTTPS'] === 'on' ? "https" : "http"). "://$_SERVER[HTTP_HOST]";
    }
    public static function make(string|null $route = null):string
    {
        return is_null($route) ? self::base() : self::base() . $route;
    }
    public static function getSegments(string $url):array
    {
        $url = strtok($url , "?");
        $url = str_replace(["http://", "https://"], ["",""], $url);
        $segments = explode("/" , $url);
        return $segments;
    }
}