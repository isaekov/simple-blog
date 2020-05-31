<?php

namespace src\controller;

use Carbon\Carbon;
use Cassandra\Date;
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
    public function post($id)
    {

        $views = new ViewsPost();
        $views->query("UPDATE java.views_post t SET t.views = t.views + 1 WHERE t.post_id = $id");
        $post = new Post();
        $post = $post->temporarilyJoinById($id);
        if (empty($post)) {
            $this->renderError404();
            return;
        }
        $post["create_date"] = Carbon::parse($post["create_date"])->isoFormat('ll');
        $this->render(["post" => $post]);
    }

    public function posts()
    {
        $postService = new PostService();
        $this->renderH(
            "post/category.twig",
            array_merge(
                $postService->getStartPage(),
                [ "check" => $this->auth->check(),
                "email" => $this->auth->getEmail()]
            )
        );
    }

    public function category($name, PostService $postService)
    {

//        $category = $postService->getCategoryPage($name);
//        $category ?: $this->renderError404();
//        $this->render(["posts" => $category]);
    }

    private function ip()
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
    }
}