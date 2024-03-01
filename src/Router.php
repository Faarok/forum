<?php

namespace App;

use AltoRouter;

class Router
{

    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct(string $viewPath)
    {

        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null)
    {
        // $this->router->map('GET', $url, fn() => require_once($this->viewPath . str_replace(array('/', '\\'), SLASH, $template) . '.php'), $name);
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    public function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        ob_start();
        require $this->viewPath . $view . '.php';
        $content = ob_get_clean();
        require $this->viewPath . SLASH . 'layouts' . SLASH . 'default.php';

        return $this;
    }
}
