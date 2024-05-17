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

    public function get(string $url, string $view, ?string $name = null)
    {
        $this->router->map('GET', $url, $view, $name);
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

        $view = $match['target'];
        ob_start();
        require $view . '.php';
        $content = ob_get_clean();
        require VIEW_PATH . 'layouts' . SLASH . 'default.php';

        return $this;
    }
}
