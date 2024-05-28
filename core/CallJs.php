<?php

namespace Core;

use Core\Exception\JsException;

class CallJs
{
    public static function listFiles():array
    {
        $files = array_diff(scandir(JS_PATH), array('..', '.', 'node_modules'));
        $path = JS_PATH;
        $scripts = array();

        foreach($files as $file)
        {
            if(!is_dir($path . $file))
                continue;
            else
            {
                $jsFiles = array_diff(scandir($path . $file), array('..', '.'));
                foreach($jsFiles as $jsFile)
                {
                    $fullFilePath = $path . $file . SLASH . $jsFile;
                    if(is_dir($fullFilePath))
                        throw new JsException('Impossible d\'inclure des sous-dossiers dans les composants JavaScripts : ' . $fullFilePath);

                    if(pathinfo($jsFile, PATHINFO_EXTENSION) === 'js')
                        $scripts[] = '<script type="module" src="' . JS_PATH_URL . $file . SLASH . $jsFile . '"></script>';
                    else
                        throw new JsException('Impossible d\'inclure des fichiers non-JavaScript : ' . $fullFilePath);
                }
            }
        }

        return $scripts;
    }
}