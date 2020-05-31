<?php

namespace src\controller;

use src\domain\IndexModel;
use src\domain\Meta;
use src\entity\Category;
use src\entity\Post;
use src\entity\User;
use src\model\Save;
use src\service\AdminService;
use System\controller\Controller;
use System\model\Model;
use System\tools\Tools;

class AdminController extends Controller
{
    public function index()
    {
        $this->render();
    }

    public function creator()
    {

        $data = (new Category())->getAll()->desc("id")->confirm();
        $user = new User();

        $set = $user->getOne()->confirm();

        $this->render(["data" => $data, "setting" => $set]);
    }

    public function addPostAction($data)
    {
        dd(json_decode($data));
        exit;
    }

    public function groupNameAction()
    {
        $category = new Category();
        $model = new IndexModel();
        $model->query("INSERT INTO `category` (`name`) VALUES ('" .Tools::post()["name"] . "')");
        $data =$category->getAll()->desc("id")->confirm();
        echo $this->getRender(["data" => $data]);
    }

    public function settingAction()
    {
        $model = new IndexModel();
        $model->query("UPDATE `user` SET `admin-additional` = '" .Tools::post()["check"] . "' WHERE `user`.`id` = 1;");
    }

    public function save()
    {
        $service = new AdminService();
        if (isset($_POST["post_id"])) {
            $service->updatePost($_POST);
        } else {
            $service->preparePostSave($_POST);
        }
    }




    public function listPosts()
    {
        $post = new Post();
        $posts = $post->temporarilyJoin();


        $this->render(["posts" => $posts]);
    }





    public function filterGroup()
    {
        if ($_POST["category_id"] > 0) {
            $posts = (new Post())->getAll()->where("category_id", Tools::post()["category_id"])->confirm();
            echo $this->getRender(["posts" => $posts]);
        }
    }
//
//    public function example()
//    {
//        $model = new Post();
//        $a = $model->getOne("post");
//        $this->render(["name" => $a["content"]]);
//    }

    public function post($id)
    {
        $model = new Post();
        $a = $model->getOne()->where("post_id", $id)->confirm();
        if (!empty($a)) {
            $this->render(["name" => $a["content"]]);
        } else {
            $this->renderH("error/notfound.twig", ["title" => "404 error"]);
        }
    }

    public function edit($id)
    {
        $post = new Post();
        $category = new Category();
        $meta = new Meta();
        $user = new User();
        $post = $post->temporarilyJoinById($id);
        if (empty($post)) {
            $this->renderH("error/notfound.twig", ["title" => "404 error"]);
            return;
        }


//        $metas = $meta->getAll()->where("post_id", $post["id"])->confirm();
        $set = $user->getOne()->confirm();
        $categories = $category->getAll()->confirm();
//        dd($post);
        $this->render(["post" => $post, "data" => $categories,  "setting" => $set]);
    }

    public function deleteMeta($id, $postId)
    {
        dd($id . $postId);
        $meta = new Meta();
        $meta->delete($id);
    }
}