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

    public function run()
    {
        global $_;

        $match = $this->router->match();

        // Vérifie si $match['target'] est false (aucune route trouvée)
        if($match === false)
            return HttpError::handle(404);

        $params = array_merge($match['params'], $_);

        // Extraction du contrôleur et de la méthode
        $target = $match['target'];
        list($controller, $method) = explode('@', $target);

        // Appel du contrôleur et de la méthode
        if(class_exists($controller) && method_exists($controller, $method))
        {
            $controllerInstance = new $controller();
            call_user_func_array(array($controllerInstance, $method), $params);
        }
        else
            HttpError::handle(500);

        return $this;
    }
}