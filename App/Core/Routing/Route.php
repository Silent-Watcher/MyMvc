<?php 
namespace App\Core\Routing;
defined("BASE_PATH") OR die("<h1>You do not have access to this section</h1>");


use App\Exceptions\invalidHttpVerbException;


class Route 
{
    const BASE_MIDDLEWARE_NAMESPACE = " App\Middlewares\\";
    private static array $routes = [];

    public static function add(string|array $httpVerb , string $uri  , string|callable $controller ):object
    {
        $httpVerb = (is_array($httpVerb)) ? array_map("strtolower", $httpVerb) : [strtolower(($httpVerb))];
        self::$routes[] = ["httpVerb" => $httpVerb,
                           "uri" => rtrim($uri,"/"),"action" => $controller];
        return new self;
    }
    public static function getRoutes():array
    {
        return self::$routes;
    }
    public static function Middleware(string|array $middlewares):void
    {
        $middlewares = (is_array($middlewares)) ? $middlewares : [$middlewares];
        self::$routes[sizeof(self::$routes) - 1]["middleware"] = $middlewares;
    }
    public static function __callStatic(string $name , array $arguments):object
    {
        $validHttpVerbs = ["get","post","put","delete"];
        [$uri , $controller ] = [$arguments[0] , $arguments[1]];
        if(!in_array($name , $validHttpVerbs))
            throw new invalidHttpVerbException();
        return self::add($name , $uri , $controller );
    }
    
}