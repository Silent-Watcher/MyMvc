<?php
require_once "bootstrap/init.php";

use App\Core\Routing\Route;
use App\Core\Routing\Router;

$router = new Router($request, Route::getRoutes(), $globalMiddlewares);
$router -> run();