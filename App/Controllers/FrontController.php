<?php 
namespace App\Controllers;

use App\Controllers\Contracts\BaseController;

class FrontController extends BaseController
{
    public function index()
    {
        $data = ["title" => "front!"];
        $this-> view(path: "pages.index" , data: $data); 
    }
}