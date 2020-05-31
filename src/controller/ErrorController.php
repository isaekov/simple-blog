<?php


namespace src\controller;


use System\controller\Controller;

class ErrorController extends Controller
{

    public function notFound()
    {
       $this->renderError404();
    }
    public function serverError()
    {
        $this->serverError505();
    }
}