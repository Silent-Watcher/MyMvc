<?php 
namespace App\Middlewares\globals;

use App\Middlewares\contracts\MiddlewareInterface;
use DeviceDetector\Parser\Client\Browser as ClientBrowser;
use hisorange\BrowserDetect\Parser as Browser;

class BlockIe implements MiddlewareInterface
{
    public function handle()
    {
        // if(Browser::isIE()) die("ie browser blocked");
        echo("a global middleware [blockie]");
    }
}