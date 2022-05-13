<?php defined("BASE_PATH") OR die("<h1>You do not have access to this section</h1>");

use App\Exceptions\invalidViewFileName;

function view(string $path , array $data = []):void
{
    $pathSegments = str_replace(".", "/", $path);
    $filePath = BASE_PATH . "/views/{$pathSegments}.php";
    if(!file_exists($filePath))
        throw new invalidViewFileName("file {$filePath} not found");
    extract($data);
    require_once $filePath;
    die();
}