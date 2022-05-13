<?php 
namespace App\Models\Contracts;

use App\Models\Contracts\CrudInterface;

abstract class BaseModel implements CrudInterface 
{
    protected $connection;
    protected string $table;
    protected string $primaryKey = "id";
    protected int $pageSize;
    protected array $attributes= [];
    protected function __construct(){}

    public function getAttribute(string $key):mixed
    {
        return (!array_key_exists($key , $this->attributes)) ? null : $this-> attributes[$key];
    }

    public function setAttribute(string $key, mixed $value):int
    {
        if(array_key_exists($key , $this->attributes))
        {
            $this->attributes[$key] = $value;
            return 1;
        }
        return 0;
    }
    
    public function getAttributes():array
    {
        return $this->attributes;
    }
}