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

// Configuration SMTP
ini_set('SMTP', $_ENV['SMTP_HOST']);
ini_set('smtp_port', $_ENV['SMTP_PORT']);

if($_ENV['APP_DEBUG'] === 'true')
{
    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
}

$router = new Router();

$router
    ->get('/migration', __ROOT__ . 'migration' . SLASH . 'migration', 'Migration')
    ->get('/', VIEW_PATH . 'home', 'Accueil')
    ->get('/sign-in', VIEW_PATH . 'auth' . SLASH . 'sign-in', 'Se connecter')
    ->get('/sign-up', VIEW_PATH . 'auth' . SLASH . 'sign-up', 'S\'inscrire')
    ->get('/blog', VIEW_PATH . 'post' . SLASH . 'index', 'Blog')
    ->get('/blog/category', VIEW_PATH . 'category' . SLASH . 'show', 'Catégorie')
    ->run();