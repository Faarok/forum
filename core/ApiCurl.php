<?php

namespace Core;

use Exception;
use CurlHandle;
use Core\Exception\ApiException;

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
     * @throws ApiException L'URL définie est vide
     */
    public function __construct(string $url)
    {
        try
        {
            if(!empty($url))
            {
                $this->url = $url;
                self::curlInit();
            }
            else
                throw new ApiException('URL vide');
        }
        catch(Exception|ApiException $error)
        {
            throw $error;
        }
    }

    /**
     * Initialise libcurl à partir de l'URL donnée dans le constructeur
     *
     * @return CurlHandle
     */
    protected function curlInit()
    {
        $this->api = curl_init($this->url);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);

        return $this;
    }

    public function setOption(int $option, mixed $value)
    {
        try
        {
            if(empty($option) || empty($value) && $value !== false)
                throw new ApiException('setOption : Option ou valeur vide', 400);

            if(!curl_setopt($this->api, $option, $value))
                throw new ApiException('L\'option n\'a pas pu être configurée. Vérifiez si elle est disponible ici : https://www.php.net/manual/en/function.curl-setopt.php');

            return $this;
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

            return $this;
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
            $this->setOption(CURLOPT_HEADER, $value);

            return $this;
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
            $this->setOption(CURLOPT_CUSTOMREQUEST, $method);

            return $this;
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
            $this->setOption(CURLOPT_POSTFIELDS, json_encode($post));

            return $this;
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
            $this->setOption(CURLOPT_HTTPHEADER, $header);

            return $this;
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

            switch($code)
            {
                case 200:
                    return json_decode($data, true);
                    break;
                case 201:
                    return json_decode($data, true);
                    break;
                case 401:
                    $data = json_decode($data, true);
                    throw new Exception($data['errors']['message'], $data['errors']['code']);
                    break;
                default:
                    throw new Exception($data, $code);
                    break;
            }

            curl_close($this->api);
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