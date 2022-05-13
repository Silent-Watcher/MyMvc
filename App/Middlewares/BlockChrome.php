<?php 
namespace App\Middlewares;

use hisorange\BrowserDetect\Parser as Browser;
use App\Middlewares\contracts\MiddlewareInterface;

class BlockChrome implements MiddlewareInterface{
    public function handle()
    {
        if (Browser::isChrome()) die("chrome blocked");
    }
}