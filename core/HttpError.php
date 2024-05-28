<?php

namespace Core;

class HttpError
{
    /**
     * Gérer une erreur HTTP.
     *
     * @param int $code Code de statut HTTP
     * @param string $message Message d'erreur (facultatif)
     * @return void
     */
    public static function handle(int $code):void
    {
        http_response_code($code);
        $message = '';

        switch($code)
        {
            case 404:
                $message = 'Page non trouvée';
                break;
            case 500:
                $message = 'Erreur interne du serveur';
                break;
            default:
                $message = 'Erreur';
                break;
        }

        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        // Inclure un fichier de vue d'erreur si disponible
        $viewPath = __ROOT__ . 'error.php';

        if(file_exists($viewPath))
        {
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            require VIEW_PATH . 'layouts' . SLASH . 'default.php';
        }
    }
}
