<?php

namespace System\tools;

use Carbon\Carbon;

class Tools
{

    public static function camelToSnakeForClassTwig($className, $var = null)
    {
        preg_match_all('/\w+$/m', self::getClass($className), $matches, PREG_SET_ORDER, 0);
        $snake = preg_replace('/[A-Z]/', '-$0', end($matches)[0]);
        $render = ltrim(strtolower($snake), '-') . '/' . self::getMethod() . ".twig";
        return $render;
    }



    public static function classNameEntity($class)
    {
        return str_replace("src\\entity\\", "", $class);
    }


    private  static function getClass($className)
    {
        return str_replace('Controller', '', explode('\\', $className)[2]);
    }

    public static function camelToSnakeForVar($name)
    {

            return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));


    }


    public static function camelToSnakeForClass($className)
    {
        preg_match_all('/\w+$/m', $className, $matches, PREG_SET_ORDER, 0);
        $snake = preg_replace('/[A-Z]/', '_$0', end($matches)[0]);
        $render = ltrim(strtolower($snake), '_');
        return $render;
    }

    private static function getMethod()
    {
        return str_replace('Action', '', debug_backtrace()[3]['function']);
    }

    public static function reload($path)
    {
        echo "<meta http-equiv='refresh' content='0;$path'>";
    }

    public static function redirect(string $url)
    {
       header('Location: '.$url);
       die;
    }
    public static function redirectJs(string $url)
    {
       echo "window.location.href = '$url'";
       die;
    }

    public static function post()
    {
        return $_POST ?? [];
    }

    public static function get()
    {
        return $_GET ?? [];
    }

    public static function request()
    {
        return $_REQUEST ?? [];
    }

    public static function date($date)
    {
       return Carbon::parse($date)->isoFormat('ll');
    }

    public static function requestDate($var1, $var2) {

    }

    public function str(string $string)
    {
        mb_internal_encoding("UTF-8");
        return mb_substr($string,0,200);
    }

    public function strToLower($str)
    {
        return mb_strtolower($str);
    }
}