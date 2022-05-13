<?php 
namespace App\Models\Contracts;
use App\Models\Contracts\BaseModel;

class JsonBaseModel extends BaseModel
{
    private string $dbFolder;
    private string $tableFilePath;

    public function __construct()
    {
        $this-> dbFolder = BASE_PATH . "/storage/jsonDB/";
        $this-> tableFilePath =  $this-> dbFolder . $this->table . ".json";
    }
    public function create(array|object $data):int
    {
        $tableFileData = $this-> readJson(assoc:true);
        $tableFileData[] = $data;
        $result = $this->writeJson($tableFileData);
        return (!$result) ? 0 : 1;
    } 
    public function find(mixed $primaryKey):array|object|null|false
    {
        $tableFileData = $this->readJson(assoc:true);
        foreach ($tableFileData as $row)
        {
            if(!array_key_exists($this->primaryKey ,$row))
                return false;
            if($row[$this->primaryKey] == $primaryKey)
                return $row;
        }
        return null;
    }
    public function get(array $columns , array|string $condition):array
    {
        return [];
    }
    public function update(array $data, array $condition):int
    {
        return 1;
    }
    public function delete(mixed $primaryKey):int
    {
        return 1;
    }
    private function readJson(bool $assoc = false):array|object
    {
        $jsonFile = file_get_contents($this->tableFilePath);
        $result = json_decode($jsonFile , $assoc);
        return $result;
    }
    private function writeJson(array|object $data):int
    {
        $json = json_encode($data);
        $writeResult = file_put_contents($this->tableFilePath ,$json);
        return $writeResult;
    }
}