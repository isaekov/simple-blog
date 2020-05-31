<?php


namespace src\entity;


use System\model\Model;

class Like extends Model
{
    private int $postId;

    private int $like;

    private int $disLike;
}