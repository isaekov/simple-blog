<?php

use System\tools\Tools;
use Twig\Environment;
use Twig\Extension\DebugExtension;

return [


    Environment::class =>
        function () {
            $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../src/view');
            $twig = new Twig\Environment($loader, [
                'debug' => true,
                'cache' => false
            ]);
            $twig->addGlobal("tools", new Tools());

            $twig->addExtension(new DebugExtension());


//             $function = new \Twig\TwigFunction();
            $tools = new Tools();

            $twig->addFunction(new Twig\TwigFunction("dateI", [$tools, "date"]));
            return $twig;

        },

    \PDO::class => function() {
        return new PDO("mysql:host=localhost;dbname=java;charset=utf8", "root", "6", [
            PDO::ATTR_PERSISTENT => true
        ]);
    },

];
