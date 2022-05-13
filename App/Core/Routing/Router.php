<?php 
namespace App\Core\Routing;

use App\Middlewares\GlobalMiddlewares;
use App\Exceptions\InvalidControllerClassName;
use App\Exceptions\InvalidMiddleWareException;
use App\Exceptions\InvalidControllerMethodName;

class Router 
{
    private const BASE_CONTROLLER_NAMESPACE = "App\Controllers\\";
    private const BASE_MIDDLEWARE_NAMESPACE = "App\Middlewares\\";
    private const BASE_GLOBAL_MIDDLEWARE_NAMESPACE = "App\Middlewares\globals\\";

    private object $request;
    private array $routes;
    private array|null $currentRoute;
    private object $globalMiddlewares;

    public function __construct(object $request , array $routes, object $globalMiddlewares) 
    {
        $this-> request = $request;
        $this-> routes = $routes;
        $this->currentRoute = $this-> findRoute() ?? null;
        $this-> globalMiddlewares =  $globalMiddlewares;
    }

    public function run():void
    {
        if(is_null($this->currentRoute))
            $this->dispatch404();
        if($this->isInvalidHttpVerb())
            $this->dispatch405();
        $this-> runGlobalMiddlewares($this-> globalMiddlewares);
        $this-> RunMiddlewares();
        $this-> dispatch();
    }
    private function dispatch404():void
    {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        view("errors.404");
    }
    
    private function dispatch405():void
    {
        header("{$_SERVER['SERVER_PROTOCOL']} 405 Method Not Allowed");
        view("errors.405");
    }

    private function dispatch():void
    {
      
        $action = $this->currentRoute["action"];
        
        if(is_null($action))
            return;

        if(is_callable($action))
            die($action());

        if(is_string($action)) # and check for regex !
            $routeControllerParts = explode("@" , $action);
       
        if(is_array($routeControllerParts))
        {
            list($controllerClass , $controllerMethod) = $routeControllerParts;
            $controllerClass = self::BASE_CONTROLLER_NAMESPACE . $controllerClass;
            $this->isAValidController(controllerClass : $controllerClass , controllerMethod: $controllerMethod );
            $controller = new $controllerClass();
            $controller -> {$controllerMethod}();
        }
    }

    private function findRoute():array|null 
    {
        foreach ($this->routes as $route )
            if($this->isRoutePregMatched($route["uri"]))
                return $route;
        return null;
    }
    private function isInvalidHttpVerb():bool
    {
        foreach ($this->routes as $route )
            if($this->isRoutePregMatched($route["uri"]) and !in_array($this->request->getMethod() , $route["httpVerb"]))
                return true;
        return false;
    }
    private function isAValidController($controllerClass , $controllerMethod):void
    {
        if(!class_exists($controllerClass))
            throw new InvalidControllerClassName("Controller '{$controllerClass}' does not exist");
        if(!method_exists($controllerClass , $controllerMethod ))
            throw new InvalidControllerMethodName("method '{$controllerMethod}' not found in {$controllerClass}");
    }
    private function RunMiddlewares():void
    {
        if(is_null($this->currentRoute["middleware"] ?? null))
            return;
        foreach ($this->currentRoute["middleware"] as $middlewareClass)
        {
           $middlewareClass = self::BASE_MIDDLEWARE_NAMESPACE.$middlewareClass;
           if(!class_exists($middlewareClass))
                throw new InvalidMiddleWareException("middleware not found");
           $middlewareObj = new $middlewareClass();
           $middlewareObj -> handle();
        }
    }
    private function runGlobalMiddlewares(GlobalMiddlewares $globalMiddlewares):void
    {
       $globalMiddlewaresClasslist = $globalMiddlewares->get();
       if(empty($globalMiddlewaresClasslist))
            return;
       foreach($globalMiddlewaresClasslist as $globalMiddlewareClass)
       {
          $globalMiddlewareClass = self::BASE_GLOBAL_MIDDLEWARE_NAMESPACE.$globalMiddlewareClass;
          $globalMiddlewaresObj = new $globalMiddlewareClass();
          $globalMiddlewaresObj -> handle();
       }
    }
    private function isRoutePregMatched(string $routeUri):bool
    {
        $pattern = "/^" . str_replace(["/", "{" , "}"],["\/", "(?<", ">[-%\w]+)" ] , $routeUri) . "$/";
        $result = preg_match($pattern,$this->request->getUri(), $matches);
        if(!$result) return false;
        foreach ($matches as $key => $value)
            if(!is_int($key))
                $this->request->addRouteParam($key , $value);
        return true;
    }
}