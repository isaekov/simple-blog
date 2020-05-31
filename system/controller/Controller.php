<?php

namespace System\controller;

use Delight\Auth\Auth;
use src\entity\Category;
use src\service\PostService;
use System\model\Model;
use System\tools\Tools;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller
{
    public Environment $twig;

    public Tools $tools;

    private array $method;

    public Auth $auth;

    public PostService $postService;

    public function __construct(Environment $twig, Tools $tools, Auth $auth, PostService $postService)
    {
        $this->twig = $twig;
        $this->auth = $auth;
        $this->tools = $tools;
        $this->method = $_REQUEST;
        $this->postService = $postService;
    }

    protected function render(array $var = []): void
    {
        try {
            $category = new Category();
            $url = $this->tools->camelToSnakeForClassTwig(get_called_class());
            $content = $this->twig->render($url, $var);
            $checkStatus = false;
            $p = explode("/", $url);
            if ($p[0] == "admin") {
                $checkStatus = true;
            }
            $admin = [
                "check" => $this->auth->check(),
                "email" => $this->auth->getEmail(),
                "date" => time()

                ];
            echo $this->twig->render("bash.twig", array_merge($var, ["content" => $content, "checkStatus" => $checkStatus, "admin" => $admin, "sidebar" => $category->getAll()->confirm()]));
        } catch (LoaderError $e) {
            echo $e->getMessage();
        } catch (RuntimeError $e) {
            echo $e->getCode();
        } catch (SyntaxError $e) {
            echo $e->getCode();
        }
    }

    protected function renderH(string $template, array $var = []): void
    {
        try {
            $category = new Category();
            $content = $this->twig->render($template, $var);
            echo $this->twig->render("bash.twig", ["content" => $content,
                "title" => $var["title"] ??= "HelloWorld", 'date' => time(), "sidebar" => $category->getAll()->confirm(),
                "check" => $var["check"]]);
        } catch (LoaderError $e) {

        } catch (RuntimeError $e) {

        } catch (SyntaxError $e) {
        }
    }

    protected function renderError404(string $template = null, array $var = [])
    {
        try {
            echo $this->twig->render("error/notFound.twig");
//            echo $this->twig->render("bash.twig", ["content" => $content, "title" => $var["title"] ??= "HelloWorld"]);
        } catch (LoaderError $e) {

        } catch (RuntimeError $e) {

        } catch (SyntaxError $e) {
        }
        return exit();
    }

    protected function serverError505(string $template = null, array $var = []): void
    {
        try {
            echo $this->twig->render("error/serverError.twig");
//            echo $this->twig->render("bash.twig", ["content" => $content, "title" => $var["title"] ??= "HelloWorld"]);
        } catch (LoaderError $e) {

        } catch (RuntimeError $e) {

        } catch (SyntaxError $e) {
        }
    }

    protected function getRender($var = [])
    {
        return $this->twig->render($this->tools->camelToSnakeForClassTwig(get_called_class()), $var);
    }

//    protected function post($name = "") {
//        if (empty($name)) return $_POST;
//        return $_POST[$name] ;
//    }

    protected function renderApi(array $var): void
    {
        echo json_encode($var);
    }
}