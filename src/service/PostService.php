<?php


namespace src\service;


use src\domain\Post;
use src\domain\ViewsPost;
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

    public function getViewOnePost($id)
    {
        $views = new ViewsPost();
        $views->query("UPDATE java.views_post t SET t.views = t.views + 1 WHERE t.post_id = $id");
        $post = new Post();
        return $post->temporarilyJoinById($id);

    }
}