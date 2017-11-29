<?php

namespace Vdhicts\Neostrada;

final class Client
{
    /**
     * Defines the Client version.
     */
    const CLIENT_VERSION = '1.0.0';
    /**
     * Defines the API endpoint.
     */
    const API_ENDPOINT = 'https://api.neostrada.nl/';
    /**
     * Contains the Neostrada class instance (singleton).
     * @var Client|null
     */
    private static $instance = null;
    /**
     * Contains the neostrada api key.
     * @var string
     */
    private $apiKey = '';
    /**
     * Contains the neostrada api secret.
     * @var string
     */
    private $apiSecret = '';
    /**
     * Contains the cURL session.
     * @var Resource|false
     */
    private $curlHandler = false;
    /**
     * Contains the cURL request result.
     * @var string
     */
    private $xmlResponse = false;

    /**
     * Returns the Neostrada class instance and creates it if needed
     * @returns Client
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Client();
        }

        return self::$instance;
    }

    /**
     * Returns the Neostrada API key.
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Stores the Neostrada API key.
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Returns the Neostrada API secret.
     * @return string
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * Stores the Neostrada API secret.
     * @param string $apiSecret
     * @return string
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    /**
     * Returns the XML response
     * @return string
     */
    public function getXmlResponse()
    {
        return $this->xmlResponse;
    }

    /**
     * Stores the xml response.
     * @param string $xmlResponse
     */
    private function setXmlResponse($xmlResponse)
    {
        $this->xmlResponse = $xmlResponse;
    }

    /**
     * Opens a new cURL session for the requested action.
     * @param string $action
     * @param array $parameters
     * @return bool
     */
    public function prepare($action, array $parameters = [])
    {
        // Close current curl handler
        $this->close();

        // Start a new curl session
        $this->curlHandler = curl_init();
        if ($this->curlHandler === false) {
            return false;
        }

        // Configure curl for the API request
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curlHandler, CURLOPT_URL, $this->getApiRequestUrl($action, $parameters));
        curl_setopt($this->curlHandler, CURLOPT_HEADER, 0);
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);

        return true;
    }

    /**
     * Executes the API request returns the result.
     * @return bool
     */
    public function execute()
    {
        // The curl session must be started
        if ($this->curlHandler === false) {
            return false;
        }

        // Perform the request
        $this->setXmlResponse(curl_exec($this->curlHandler));

        // Close the curl handler
        $this->close();

        // The response is false when the request failed
        return $this->getXmlResponse() !== false;
    }

    /**
     * Returns the fetched result from the cURL session
     * Note: only works after Execute() is called!
     */
    public function fetch()
    {
        if ($this->getXmlResponse() === false) {
            return false;
        }

        // Prevent XML errors
        libxml_use_internal_errors(false);

        $xml = simplexml_load_string($this->getXmlResponse());
        if ($xml === false) {
            return false;
        }

        $result = [];

        // Stores the attributes
        foreach ($xml->attributes() AS $attribute) {
            /** @var \SimpleXMLElement $attribute */
            $result[strtolower($attribute->getName())] = trim((string)$attribute);
        }

        // Parse the children
        foreach ($xml->children() AS $child) {
            /** @var \SimpleXMLElement $child */
            if ($child->count() > 0) {
                foreach ($child->children() AS $subChild) {
                    $result[strtolower($child->getName())][] = trim((string)$subChild);
                }
            } else {
                $result[strtolower($child->getName())] = trim((string)$child);
            }
        }

        return $result;
    }

    /**
     * Returns the request url for the API request.
     * @param string $action
     * @param array $parameters
     * @return string
     */
    private function getApiRequestUrl($action, array $parameters = [])
    {
        return sprintf(
            '%s?api_key=%s&action=%s&%s&api_sig=%s',
            self::API_ENDPOINT,
            $this->getApiKey(),
            $action,
            http_build_query($parameters),
            $this->getApiSignature($action, $parameters)
        );
    }

    /**
     * Returns the signature for the API request.
     * @param string $action
     * @param array $parameters
     * @return string
     */
    private function getApiSignature($action, array $parameters = [])
    {
        $apiSignature = sprintf(
            '%s%saction%s',
            $this->getApiSecret(),
            $this->getApiKey(),
            $action
        );
        foreach ($parameters AS $key => $value) {
            $apiSignature .= $key . $value;
        }

        return md5($apiSignature);
    }

    /**
     * Close the current cURL session.
     */
    private function close()
    {
        if ($this->curlHandler !== false) {
            curl_close($this->curlHandler);
        }

        $this->curlHandler = false;
    }
}
