<?php

namespace App\Repository;

abstract class AbstractRepository
{
    protected \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}