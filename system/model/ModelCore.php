<?php


namespace System\model;


use System\db\Database;
use System\db\Repository;
use System\db\SqlCreator;
use System\tools\Tools;
use function DI\string;

abstract class ModelCore extends Database implements Repository
{
    public int $index = 0;

    private string $select;

    private array $where;

    private string $groupBy;

    private string $sortBy;

    public array $logic;

    private string $sql;

    private bool $allOrOne;

    public  function getOne()
    {
        $this->allOrOne = false;
        $this->select = "SELECT * FROM `" . Tools::camelToSnakeForVar( Tools::classNameEntity(static::class)) . "`";
        return $this;
    }



    public function getAll()
    {
        $this->allOrOne = true;
        $this->select = "SELECT * FROM " . Tools::camelToSnakeForVar( Tools::classNameEntity(static::class));
        return $this;
    }

    public function where(string $field, string $value)
    {
        $this->where[$this->index] = "`$field` =  '$value'";
        return new Logic($this, $this->index);
    }

    public function temporarilyJoin()
    {
        $sql = "SELECT v.views, c.name, p.* FROM java.post AS p
                LEFT JOIN java.category  AS c ON p.category_id = c.id
                LEFT JOIN java.views_post AS v ON p.post_id = v.post_id
                ORDER BY RAND()";
        return $this->pdo->query($sql)->fetchAll(2);
    }
    public function temporarilyJoinById($id)
    {
        $sql = "SELECT l.likes, l.dis_like, c.*, p.* FROM java.post AS p
                LEFT JOIN java.category  AS c ON p.category_id = c.id
                LEFT JOIN java.assessment AS l ON p.post_id = l.post_id
                WHERE p.post_id = $id";
        return $this->pdo->query($sql)->fetch(2);
    }

    public function getJoinCategory($name)
    {
        $sql = "SELECT * FROM java.category 
                LEFT JOIN java.post ON java.post.category_id = java.category.id
                LEFT JOIN java.views_post ON java.views_post.post_id = java.post.post_id
                WHERE java.category.name LIKE '$name'";
        return $this->pdo->query($sql)->fetchAll(2);
    }

//    public function temporarilyJoinByIdForUpdatePost($id)
//    {
//        $sql = "SELECT c.meta, p.* FROM java.post AS p
//                JOIN java.meta  AS c ON p.id = c.post_id
//                WHERE p.id = $id";
//        return $this->pdo->query($sql)->fetchAll(2);
//    }





    public function desc(string $field)
    {
        $this->sortBy = " ORDER BY $field DESC ";
        return $this;
    }





    public function confirm()
    {
        if (!empty($this->select)) {
            $this->sql = $this->select;
        }

        if (!empty($this->select) && !empty($this->where) and is_array($this->where)) {
            $this->sql .= " WHERE ";
            foreach ($this->where as $key => $item) {
                if (isset($this->logic[$key])) {
                    $this->sql .= $item . $this->logic[$key];
                } else {
                    $this->sql .= $item;
                }
            }
        }

        if (!empty($this->select)  && !empty($this->sortBy)) {
            $this->sql .= $this->sortBy;
        }
        if ($this->allOrOne) {
           return $this->pdo->query($this->sql)->fetchAll(2);
        } else {
           return $this->pdo->query($this->sql)->fetch(2);

        }
    }

    public function update($where)
    {

    }

    public function delete($id, $postId)
    {
        $this->pdo->query("DELETE FROM " . Tools::camelToSnakeForVar( Tools::classNameEntity(static::class)) .  " WHERE id = $id AND post_id = $postId");
    }

    public function insert($insert)
    {

    }

    public function query($sql)
    {
        $this->pdo->exec($sql);
    }

    public function getDb()
    {
        return $this->pdo;
    }





}