<?php

namespace App\Providers;

use App\Interfaces\IMongoDB;
use MongoDB\Client;
use Exception;


class MongoDBProvider implements IMongoDB
{

    private $mongodb;
    private $collection;

    public function connection($user, $password, $url, $database, $source)
    {
        $this->mongodb = new Client("mongodb://{$user}:{$password}@{$url}/{$database}?authSource={$source}");
        return $this->mongodb;
    }

    public function collection($database, $collection){
        $this->collection = $this->mongodb->selectCollection($database, $collection);
    }

    public function find(array $search)
    {
        try {
            return $this->collection->find($search);
        } catch (Exception $ex){
            logger('MongoDBException')->error($ex->getMessage());
        }
    }

    public function findOne(array $search)
    {
        try {
            return $this->collection->findOne($search);
        } catch (Exception $ex){
            logger('MongoDBException')->error($ex->getMessage());
        }
    }
}
