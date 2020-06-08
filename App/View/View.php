<?php

namespace App\View;

class View {

    public static function render($template, $args)
    {
        $loader = new \Twig\Loader\Filesystemloader(dirname(__DIR__, 2) . '/views/');
        $twig = new \Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);

        echo $twig->render($template, $args);
    }

}