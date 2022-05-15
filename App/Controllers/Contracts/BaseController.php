<?php 
namespace App\Controllers\Contracts;
abstract class BaseController 
{
    protected string $model;

    protected function view(string $path , array $data = []):void
    {
        view($path, $data);
    }
}