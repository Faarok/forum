<?php

/**
 * Class légère permettant d'initialiser libcurl.
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
        curl_setopt_array($this->api, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1
        ]);

        return $this->api;
    }

    /**
     * exec
     * Exécute libcurl à partir de la variable $this->api récupérer lors de l'initialisation de libcurl.
     *
     * @param  mixed $fields
     *
     * @param $fields
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
                    throw new Exception($data['message'], 401);
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