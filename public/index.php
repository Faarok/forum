<?php

use Whoops\Run;
use Core\Router;
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
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler());
    $whoops->register();
}

$router = new Router();

// User
$router
    ->get('/user/sign-up', 'App\Controllers\UserController@signUp', 'S\'inscrire')
    ->post('/user/create-user', 'App\Controllers\UserController@createUser', 'Création d\'un utilisateur')
    ->get('/test', 'App\Controllers\UserController@testMail')
;

// $router
//     ->get('/migration', 'App\Controllers\MigrationController@runMigration', 'Migration')
//     ->get('/', 'App\Controllers\HomeController@index', 'Accueil')
//     // ->post('/user/create', 'App\Controllers\UserController@create', 'Créer utilisateur')
//     ->get('/sign-up', 'App\Controllers\AuthController@signUp', 'S\'inscrire')
//     ->get('/blog', 'App\Controllers\PostController@index', 'Blog')
//     ->get('/blog/category', 'App\Controllers\CategoryController@show', 'Catégorie')
// ;

$router->run();