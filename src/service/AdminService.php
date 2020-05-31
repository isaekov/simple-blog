<?php

namespace src\service;

use ReflectionProperty;
use src\domain\Meta;
use src\domain\Post;
use src\domain\ViewsPost;
use System\tools\Tools;
use function DI\value;

class AdminService
{
    /**
     * @param array $var
     */
    public function preparePostSave(array $var)
    {
        $post = new Post();
        $post->pdo->beginTransaction();
        try {
            $post = $this->addDataEntity($post);
            $lastInsertPostId = $post->save();
            $count = count($var["meta"]["name"]);
            for ($i = 0; $i < $count; $i++) {
                $meta = new Meta();
                $meta->setPostId($lastInsertPostId);
                $meta->setName($var["meta"]["name"][$i]);
                $meta->setContent($var["meta"]["content"][$i]);
                $meta->save();
            }
            $viewPost = new ViewsPost();
            $viewPost->setPostId($lastInsertPostId);
            $viewPost->setViews(1);
            $viewPost->save();
            $post->pdo->commit();
            Tools::redirectJs("/admin/edit/$lastInsertPostId");
        } catch (\PDOException $e) {
            $post->pdo->rollBack();
            echo "toastr.error('Ошибка сохранений')";
        }
    }


    private function params($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return "";
    }

    public function updatePost(array $var)
    {
        $post = new Post();
        $post = $this->addDataEntity($post);
        $post->updateIns($var["post_id"]);
    }


    private function addDataEntity(Post $post) {
        $post->setTitle($this->params("title"));
        $post->setCategoryId((int)$this->params("category"));
        $post->setMonolog($this->params("monolog"));
        $post->setImage($this->params("image"));
        $post->setContent($this->params("content"));
        $post->setPublic((int)$this->params("public"));
        return $post;
    }
}