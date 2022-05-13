<?php 
namespace App\Models\Contracts;

use PDO;
use Medoo\Medoo;
use PDOException;
use App\Models\Contracts\BaseModel;
use App\Exceptions\PdoConnectionException;
use stdClass;

class MysqlBaseModel extends BaseModel
{
    public function __construct(mixed $primaryKey = null) 
    {
        try
        {
            $pdo = new PDO($_ENV["DATABASE_DSN"],$_ENV["DB_USER_NAME"],$_ENV["DB_USER_PASS"]);
            $pdo -> exec("set names utf8;");
            $pdo -> setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
            $pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_OBJ);
            $this-> connection = new Medoo(['pdo' => $pdo,'type' => 'mysql']);
        } catch (PDOException $pdoError)
        {
            throw new PdoConnectionException();
        }
        if(!is_null($primaryKey))
            return $this->find($primaryKey);
    }
    public function create(array|object $data):int
    {
        $data = (is_array($data)) ? $data : (array) $data;
        $this -> connection -> insert(table: $this -> table , values:$data);
        return $this -> connection -> id();
    }
    public function find(mixed $primaryKey):object
    {
        $result = $this -> connection -> get($this->table,"*",[$this-> primaryKey=>  $primaryKey]);
        if(is_null($result)) return new stdClass(); 
        $result = is_array($result) ? (object) $result : $result;
        foreach ($result as $column => $value)
            $this->attributes[$column] = $value;
        return $this;
    }
    public function get(array $columns , array|string $condition):array
    {
        $condition = is_string($condition) ? (array) $condition : $condition;
        $result = $this->connection->select($this->table, $columns, $condition);
        return $result;
    }
    public function getAll(array|string $columns = "*"):array
    {
        $result = $this->connection->select($this->table, $columns);
        return $result;
    }
    public function update(array $data, array $condition):int
    {
        $affectedRows = $this -> connection -> update($this->table, $data, $condition);
        return $affectedRows -> rowCount();
    }
    public function delete(array $condition):int
    {
        $affectedRows = $this -> connection -> delete($this->table,$condition);
        return $affectedRows -> rowCount();
    }
    public function remove():int
    {
        $result = $this -> delete([$this-> primaryKey => $this->getAttribute($this-> primaryKey)]);
        return ($result) ? 1 : 0;
    }
    public function save():object|null
    {
        $result = $this-> update($this->attributes,[$this-> primaryKey => $this->getAttribute($this-> primaryKey)]);
        return $this; 
    }
    public function __get(string $name):mixed
    {
        return $this->getAttribute(key:$name);
    }
    public function __set($name, $value):void
    {
        $this-> setAttribute(key:$name , value:$value);
    }
}