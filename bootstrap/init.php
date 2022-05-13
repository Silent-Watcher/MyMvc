<?php 
declare(strict_types = 1);

use App\Core\Request;
use App\Middlewares\GlobalMiddlewares;

require_once "constants.php";
require_once BASE_PATH . "/vendor/autoload.php";
require_once BASE_PATH . "/helpers/view.php";
require_once BASE_PATH . "/routes/web.php";
require_once BASE_PATH . "/helpers/dd.php";

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
require_once BASE_PATH . "/configs/dotenv.setting.php";
$request = new Request();
$globalMiddlewares = new GlobalMiddlewares();
