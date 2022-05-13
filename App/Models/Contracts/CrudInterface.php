<?php 
namespace App\Models\Contracts;
interface CrudInterface 
{
    public function create(array|object $data):int;
    public function find(mixed $primaryKey):array|object|null|false;
    public function get(array $columns , array|string $condition):array;
    public function update(array $data, array $condition):int;
    public function delete(array $condition):int;
}