<?php

namespace App;

use Exception;
use CurlHandle;
use App\Exception\ApiException;

/**
 * Class légère pour utiliser cURL
 *
 * @author Svein Samson <samson.svein@gmail.com>
 */
class ApiCurl
{

    private $url;
    private $api;

    /**
     * Donne la valeur à la variable $url lors de l'instanciation de la classe
     *
     * @param  string $url
     *
     * @throws Exception L'URL définie est vide
     */
    public function __construct(string $url)
    {
        if(!empty($url))
        {
            $this->url = $url;
            self::curlInit();
        }
        else
            throw new Exception('Undefined URL');
    }

    /**
     * Initialise libcurl à partir de l'URL donnée dans le constructeur
     *
     * @return CurlHandle
     */
    protected function curlInit():CurlHandle
    {
        $this->api = curl_init($this->url);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);

        return $this->api;
    }

    public function setOption(int $option, mixed $value)
    {
        try
        {
            if(empty($option) || empty($value) && $value !== false)
                throw new ApiException('setOption : Option ou valeur vide', 400);

            if(!curl_setopt($this->api, $option, $value))
                throw new ApiException('L\'option n\'a pas pu être configurée. Vérifiez si elle est disponible ici : https://www.php.net/manual/en/function.curl-setopt.php');

            return $this->api;
        }
        catch(Exception|ApiException $error)
        {
            $this->api->close();
            throw $error;
        }
    }

    public function setOptions(array $options)
    {
        try
        {
            if(empty($options))
                throw new ApiException('setOptions : Aucuns options trouvées.', 400);

            foreach($options as $key => $value)
                $this->setOption($key, $value);

            return $this->api;
        }
        catch(ApiException $error)
        {
            $this->api->close();
            throw $error;
        }
    }

    public function setHeaderOutput(bool $value)
    {
        try
        {
            $this->api->setOption(CURLOPT_HEADER, $value);

            return $this->api;
        }
        catch(Exception|ApiException $error)
        {
            throw $error;
        }
    }

    public function setCustomRequest(string $method)
    {
        try
        {
            $this->api->setOption(CURLOPT_CUSTOMREQUEST, $method);

            return $this->api;
        }
        catch(Exception|ApiException $error)
        {
            throw $error;
        }
    }

    public function setPostFields(array $post)
    {
        try
        {
            $this->api->setOption(CURLOPT_POSTFIELDS, json_encode($post));

            return $this->api;
        }
        catch(Exception|ApiException $error)
        {
            throw $error;
        }
    }

    public function setHeader(array $header)
    {
        try
        {
            $this->api->setOption(CURLOPT_HEADER, $header);

            return $this->api;
        }
        catch(Exception|ApiException $error)
        {
            throw $error;
        }
    }

    /**
     * exec
     * Exécute libcurl à partir de la variable $this->api récupérer lors de l'initialisation de libcurl.
     *
     * @throws Exception L'API en paramètre est vide
     * @throws Exception L'exécution a renvoyé false
     * @throws Exception L'exécution a renvoyé une erreur HTTP 401
     * @throws Exception L'exécution a renvoyé une erreur HTTP
     *
     * @return array
     */
    public function exec()
    {
        if(!empty($this->api))
        {
            $data = curl_exec($this->api);

            if($data === false)
            {
                throw new Exception(curl_error($this->api));
                self::close();
            }

            $code = curl_getinfo($this->api, CURLINFO_HTTP_CODE);
            if($code !== 200)
            {
                curl_close($this->api);
                if($code === 401)
                {
                    $data = json_decode($data, true);
                    throw new Exception($data['errors']['message'], $data['errors']['code']);
                }
                throw new Exception($data, $code);
            }

            return json_decode($data, true);
        }
        throw new Exception("CURL IS EMPTY");
    }

    /**
     * Ferme la session de cURL
     *
     * @param  CurlHandle $this->api
     *
     * @throws Exception L'API en paramètre est vide
     *
     * @return void
     */
    protected function close():void
    {
        if(!empty($this->api))
            curl_close($this->api);
        else
            throw new Exception("CURL IS EMPTY");
    }
}
?>