<?php 
namespace App\Models;

use App\Models\Contracts\MysqlBaseModel;

class User extends MysqlBaseModel
{
    protected string $table = "users";
}
