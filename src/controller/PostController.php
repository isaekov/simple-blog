<?php

namespace src\controller;

use Carbon\Carbon;
use Cassandra\Date;
use kcfinder\text;
use src\domain\Post;
use src\domain\ViewsPost;
use src\entity\Category;
use src\entity\Ip;
use src\entity\Like;
use src\model\IndexModel;
use src\service\PostService;
use System\controller\Controller;

class PostController extends Controller
{

    /**
     * @param $id int
     * Выбор одной статьии
     */
    public function post($id)
    {
        $post = $this->postService->getViewOnePost($id);
        $post ?: $this->renderError404();
        $post["create_date"] = Carbon::parse($post["create_date"])->isoFormat('ll');
        $this->render(["post" => $post, "title" => $post["title"]]);
    }

    /**
     * Вывод рандомных статей
     */
    public function posts()
    {
        $this->renderH(
            "post/category.twig",
            array_merge(
                $this->postService->getStartPage(),
                [ "check" => $this->auth->check(),
                "email" => $this->auth->getEmail()]
            )
        );
    }

    /**
     * @param $name text
     * Выбор по категориям
     */
    public function category($name)
    {
        $category = $this->postService->getCategoryPage($name);
        $category ?: $this->renderError404();
        $this->render(["posts" => $category]);
    }

   /* private function ip()
    {
        $ip = new Ip();
        $res = $ip->getDb()->query("SELECT * FROM java.ip WHERE ip LIKE '$_SERVER[REMOTE_ADDR]'")->fetch(2);
        if (empty($res)) {
            $ip->query("INSERT INTO java.ip (ip) VALUES ('$_SERVER[REMOTE_ADDR]')");
            return true;
        }
        return false;
    }

    public function like()
    {
        $postId = $_POST["postId"];
        $like = new Like();
        if ($this -> ip()) {
            $like -> query("UPDATE java.assessment l SET l.likes = l.likes + 1 WHERE l.post_id = $postId");
            $like = $like -> getOne() -> where("post_id", $postId) -> confirm();
            echo "$('.fa-thumbs-up').html(" . $like["like"] . ")";
        }
    }

    public function disLike()
    {
        if ($this->ip()) {
            $postId = $_POST["postId"];
            $like = new Like();
            $like -> query("UPDATE java.assessment l SET l.dis_like = l.dis_like + 1 WHERE l.post_id = $postId");
            $like = $like -> getOne() -> where("post_id", $postId) -> confirm();
            echo "$('.fa-thumbs-down').html(" . $like["dis_like"] . ")";
        }
    }*/
}