<?php

namespace App\Interfaces;

interface IMongoDB {

    public function connection($user, $password, $url, $database, $source);
    public function collection($databse, $collection);
    public function find(array $search);
    public function findOne(array $search);
}

?>