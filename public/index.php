<?php

use App\Router;
use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once '../vendor/autoload.php';
require_once '../function.php';
require_once '../constant.php';

$dotenv = Dotenv::createImmutable(__ROOT__);
$dotenv->safeLoad();

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$router = new Router(VIEW_PATH);

$router
    ->get('/', 'home', 'Accueil')
    ->get('/test', 'test')
    ->get('/sign-in', 'auth' . SLASH . 'sign-in', 'Se connecter')
    ->get('/sign-up', 'auth' . SLASH . 'sign-up', 'S\'inscrire')
    ->get('/blog', 'post' . SLASH . 'index', 'Blog')
    ->get('/blog/category', 'category' . SLASH . 'show', 'Catégorie')
    ->run();