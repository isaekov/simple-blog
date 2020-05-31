<?php


namespace System\db;


class SqlCreator
{
    private string $select;

    private string $where;

    private string $desc;

    private \PDO $pdo;

    public function __construct(string $select, \PDO $pdo)
    {
        $this->select = $select;
        $this->pdo = $pdo;
    }


    public function confirm() {
        $sql = $this->select;
        if (!empty($this->where)) {
            $sql .= $this->where;
        }
        if (!empty($this->desc)) {
            $sql .= $this->desc;
        }
        dd(debug_print_backtrace());
//        return $this->pdo->query($sql)->fetchAll(2);
    }

    public function where($field, $value) {
        $this->where = " WHERE $field = $value";
        return $this;
    }

    public function desc($field) {
        $this->desc = " ORDER BY $field DESC";
        return $this;
    }
}