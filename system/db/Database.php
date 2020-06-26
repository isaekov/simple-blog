<?php


namespace System\db;


use PDO;

class Database
{
    public \PDO $pdo;

    public static \PDO $pdoI;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=java;charset=utf8", "root", "zxAY38RN");

        self::$pdoI = $this->pdo;
    }
}