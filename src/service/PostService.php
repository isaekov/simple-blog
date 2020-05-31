<?php


namespace src\service;


use src\domain\Post;
use src\entity\Category;

class PostService
{

    /**
     * @return array
     */
    public function getStartPage() : array
    {
        $post = new Post();
        $posts = $post->temporarilyJoin();
        $category = new Category();
        return [
            "posts" => $posts,
            "category" => $category->getAll()->confirm(),
        ];
    }

    public function getCategoryPage($name) : array
    {
        $category = new Category();
        $category = $category->getJoinCategory($name);
        return $category;
    }
}