<?php

namespace Core;

use AltoRouter;

class Router
{

    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $target, ?string $name = null)
    {
        $this->router->map('GET', $url, $target, $name);
        return $this;
    }

    public function post(string $url, string $target, ?string $name = null)
    {
        $this->router->map('POST', $url, $target, $name);
        return $this;
    }

    public function run(): self
    {
        $match = $this->router->match();

        // Vérifie si $match['target'] est false (aucune route trouvée)
        if($match === false)
        {
            // Afficher une erreur 404
            http_response_code(404);
            ob_start();
            require(__ROOT__ . 'error.php');
            $content = ob_get_clean();
            require VIEW_PATH . 'layouts' . SLASH . 'default.php';
            return $this;
        }

        // Extraction du contrôleur et de la méthode
        $target = $match['target'];
        list($controller, $method) = explode('@', $target);

        // Appel du contrôleur et de la méthode
        if(class_exists($controller) && method_exists($controller, $method))
        {
            $controllerInstance = new $controller();
            dd($match);
            call_user_func_array(array($controllerInstance, $method), $match['params']);
        }
        else
        {
            // Afficher une erreur 500 si le contrôleur ou la méthode n'existent pas
            http_response_code(500);
            ob_start();
            require(__ROOT__ . 'error.php');
            $content = ob_get_clean();
            require VIEW_PATH . 'layouts' . SLASH . 'default.php';
            return $this;
        }

        return $this;
    }
}
