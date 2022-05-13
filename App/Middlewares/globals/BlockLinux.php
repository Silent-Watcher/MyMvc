<?php 
namespace App\Middlewares\globals;

use App\Middlewares\contracts\MiddlewareInterface;

class BlockLinux implements MiddlewareInterface
{
    public function handle()
    {
        echo("from global middleware 2");
    }
}