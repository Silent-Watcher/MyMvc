<?php 
namespace App\Middlewares;
class GlobalMiddlewares 
{
    private array $globalMiddlewares = [];
    public function __construct() {
        
        // $this-> globalMiddlewares = ["BlockIe","BlockLinux"];
    }
    public function get():array
    {
        return $this-> globalMiddlewares;
    }
}