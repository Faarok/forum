<?php

require_once '../vendor/autoload.php';
require_once '../function.php';
require_once '../constant.php';

$router = new AltoRouter();

$router->addRoutes(array(
    array('GET', '/blog', function() {
        require VIEW_PATH . 'post' . SLASH . 'index.php';
    }),
    array('GET', '/blog/category', function() {
        require VIEW_PATH . 'category' . SLASH . 'show.php';
    })
));

$match = $router->match();

call_user_func_array($match['target'], $match['params']);