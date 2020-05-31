<?php


namespace System\model;


use System\db\Database;
use System\db\Repository;
use System\tools\Tools;

abstract class Model extends ModelCore
{


    public function save() : int
    {
        $reflection = new \ReflectionObject($this);
        $prepare = [];
        $columns = [];
        $data = [];
        foreach ($reflection->getProperties() as $key => $method) {
            if ($method->class == static::class) {
                $columns[] = "`" . Tools::camelToSnakeForVar($method->name) . "`";
                $value = "get" . ucfirst($method->name);
                $data[] = $this->$value();
                $prepare[] = "?";
            }
        }
        $sql = 'INSERT INTO ' . Tools::camelToSnakeForClass(static::class) .
               '(' . implode(', ', $columns) . ')
           VALUES
                (' . implode(', ', $prepare) . ')';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function updateIns($id)
    {
        $reflection = new \ReflectionObject($this);
        $prepare = [];
        $columns = [];
        $data = [];

        foreach ($reflection->getProperties() as $key => $method) {
            if ($method->class == static::class) {
                $value = "get" . ucfirst($method->name);
                $columns[Tools::camelToSnakeForVar($method->name)] = $this->$value();
            }
        }

        $sql = [];

        foreach ($columns as $key => $val) {

                $sql[] = "`" . $key . "` = " . $this->pdo->quote($val);
        }

        $sql = "UPDATE `post` SET " . implode(", ", $sql) . " WHERE `post`.`post_id` = $id;";


        $this->pdo->exec($sql);
    }



    public function writeHack($page, $url, $method, $parameter, $ip)
    {
        $sql = "INSERT INTO java.log_id (`page`, `url`, `method`, `parameter`, `ip`) VALUES ('$page', '$url', '$method', '$parameter', '$ip')";
        $this->pdo->exec($sql);
    }


}