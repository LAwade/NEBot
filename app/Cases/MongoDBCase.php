<?php

namespace App\Cases;

use App\Interfaces\IMongoDB;
use Exception;

class MongoDBCase
{
    /**
     * @param IMongoDB $mongodb
     */
    private $mongodb;

    /**
     * @param String $database
     */
    private $database;

    public function __construct(IMongoDB $mongodb)
    {
        $this->mongodb = $mongodb;
    }

    public function connect($user, $password, $url, $database, $source)
    {
        $this->database = $database;
        $this->mongodb->connection($user, $password, $url, $database, $source);
    }

    private function collection($colletion)
    {
        $this->mongodb->collection($this->database, $colletion);
    }

    public function findOne(array $find, string $colletion)
    {
        try {
            if (!$find || !$colletion) {
                return false;
            }
            $this->collection($colletion);
            return $this->mongodb->findOne($find);
        } catch (Exception $ex) {
            logger('MongoDBCaseException')->error($ex->getMessage());
        }
    }

    public function find(array $find, string $colletion)
    {
        try {
            if (!$find || !$colletion) {
                return false;
            }

            $this->collection($colletion);
            return $this->mongodb->find($find);
        } catch (Exception $ex) {
            logger('MongoDBCaseException')->error($ex->getMessage());
        }
    }
}
