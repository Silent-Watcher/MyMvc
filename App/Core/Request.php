<?php 
namespace App\Core;
use App\Utilities\Url;
use App\Exceptions\InvalidParamValueException;

class Request
{
   private string $protocol;
   private string $method;
   private array $params;
   private string $uri;
   private string $userAgent;
   private string $route;
   private array $routeParams = [];

   public function __construct()
   {
     $this -> method = strtolower($_SERVER["REQUEST_METHOD"]);
     $this -> params = $_REQUEST;
     $this -> uri = strtok($_SERVER["REQUEST_URI"], "?");
     $this -> userAgent = $_SERVER["HTTP_USER_AGENT"];
     $this -> protocol = $_SERVER["SERVER_PROTOCOL"];
     $this -> route = $_ENV["BASE_URL"]. $this -> uri;
   }
   public function __call(string $name, array $arguments):string|array # getter functions
   {
       $name = lcfirst(substr($name,3)); #example : getUri ~> uri
       return $this -> {$name};
   }
   public function __get(string $name):mixed 
   {
       if(!isset($_GET[$name]) OR empty($_GET[$name]))
            throw new InvalidParamValueException("value '{$name}' does not exist in get query parameters");
       return $_GET[$name];
   }
   public function redirect(string $route):void
   {
       header("location:". Url::make($route));
   }
   public function isAjax():bool
   {
      return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
                 && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
   }
   public function isSecure():bool 
   {
      return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
   }
   public function addRouteParam(string $key, string|int $value)
   {
       $this-> routeParams = [$key => $value];
   }
}