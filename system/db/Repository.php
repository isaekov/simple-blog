<?php


namespace System\db;


interface Repository
{
    public  function getOne();

    public    function getAll();

    public function update($where);

    public function delete($id, $postId);

    public function insert($insert);

//    public function save();
}