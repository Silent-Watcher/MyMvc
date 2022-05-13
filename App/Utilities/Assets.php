<?php 
namespace App\Utilities;

use App\Exceptions\AssetInsertionException;

class Assets
{
    public static function __callStatic(string $name, array$arguments):string
    {
        if(empty($arguments))
            throw new AssetInsertionException("no file selected for asserting");
        $fileName =  $arguments[0];
        return $_ENV["BASE_URL"] . "/assets/{$name}/{$fileName}.{$name}";
    }
    public static function img(string $fileName):string
    {
        return $_ENV["BASE_URL"] . "/assets/img/{$fileName}";
    }
}