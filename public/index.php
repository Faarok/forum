<?php

use App\User;
use App\Router;
use Whoops\Run;
use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;

require_once '../vendor/autoload.php';
require_once '../function.php';
require_once '../constant.php';

$dotenv = Dotenv::createImmutable(__ROOT__, file_exists(__ROOT__ . '.env.dev') ? '.env.dev' : '.env');
$dotenv->safeLoad();

if($_ENV['APP_DEBUG'] === 'true')
{
    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
}

$router = new Router(VIEW_PATH);

$router
    ->get('/', 'home', 'Accueil')
    ->get('/sign-in', 'auth' . SLASH . 'sign-in', 'Se connecter')
    ->get('/sign-up', 'auth' . SLASH . 'sign-up', 'S\'inscrire')
    ->get('/blog', 'post' . SLASH . 'index', 'Blog')
    ->get('/blog/category', 'category' . SLASH . 'show', 'Catégorie')
    ->run();