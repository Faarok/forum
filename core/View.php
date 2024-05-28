<?php

namespace Core;

class View
{
    /**
     * Rendre une vue avec des données
     *
     * @param string $view Le nom du fichier de vue à rendre
     * @param array $data Les données à passer à la vue
     * @return void
     */
    public static function render(string $view, array $data = array()):void
    {
        $viewPath = VIEW_PATH . str_replace('.', SLASH, $view) . '.php';

        if(file_exists($viewPath))
        {
            // Extraire les données pour qu'elles soient disponibles sous forme de variables
            extract($data);

            // Inclure le fichier de vue
            ob_start();
            require $viewPath;
            $content = ob_get_clean();

            // Inclure le layout principal
            require VIEW_PATH . 'layouts' . SLASH . 'default.php';
        }
        else
        {
            // Afficher une erreur si le fichier de vue n'existe pas
            http_response_code(404);
            echo "Vue non trouvée : $viewPath";
        }
    }
}
