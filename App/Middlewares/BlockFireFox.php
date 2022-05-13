<?php
namespace App\Middlewares; 

use hisorange\BrowserDetect\Parser as Browser;

use App\Middlewares\contracts\MiddlewareInterface;

class BlockFireFox implements MiddlewareInterface
{
    public function handle()
    {
        if (Browser::isFirefox()) {
            die("firefox blocked");
        }
    }
}