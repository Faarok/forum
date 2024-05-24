<?php

use App\User;
use Core\Router;
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

// User
$router
    ->get('/sign-in', 'App\Controllers\User@signIn', 'Se connecter')
;

$router
    ->get('/migration', 'App\Controllers\MigrationController@runMigration', 'Migration')
    ->get('/', 'App\Controllers\HomeController@index', 'Accueil')
    // ->post('/user/create', 'App\Controllers\UserController@create', 'Créer utilisateur')
    ->get('/sign-up', 'App\Controllers\AuthController@signUp', 'S\'inscrire')
    ->get('/blog', 'App\Controllers\PostController@index', 'Blog')
    ->get('/blog/category', 'App\Controllers\CategoryController@show', 'Catégorie')
;

$router->run();