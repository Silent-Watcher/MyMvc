<?php 
namespace App\Controllers;
class FrontController 
{
    public function index()
    {
        $data = ["title" => "front!"];
        view(path: "pages.index" , data: $data); 
    }
}