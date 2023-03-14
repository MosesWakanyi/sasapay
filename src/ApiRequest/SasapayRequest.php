<?php

namespace Mosesgathecha\Sasapay\ApiRequest;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Mosesgathecha\Sasapay\Auth\SasapayAuth;
use Mosesgathecha\Sasapay\Auth\SasapayBase;
use Mosesgathecha\Sasapay\Contracts\SasapayCommunication;

class SasapayRequest extends SasapayAuth implements SasapayCommunication
{


    protected $url; // API endpoint URL
    protected $reqBody; // Request body data
    protected $response; // Response object



    public function __construct()
    {
        parent::__construct(new SasapayBase(new Client));
    }

    /**
     * Sets the request body for the API call.
     *
     * @param mixed $body The request body data.
     */
    public function setBody($param)
    {
        $this->reqBody = $param;
        return $this;
    }
    /**
     * Sets the URL for the API call.
     *
     * @param string $url The API endpoint URL.
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Executes the API call with the specified parameters.
     *
     * @param string $method The HTTP method to use (default is POST).
     * @return mixed The response from the API call or an error message.
     */
    public function execute($method = 'POST')
    {
        try {
            // Make the API call using the HTTP client with JSON data and content type header
            $apiResponse = $this->sasapayBase->client->request(
                $method,
                $this->sasapayBase->baseUrl . $this->url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->authenticate(),
                    ], 'json' => $this->reqBody,
                ]
            );
            return $apiResponse->getBody();
        } catch (ClientException $xe) {
            // Handle exceptions and return false
            $this->exception($xe->getMessage());
            return false;
        }
    }

    /**
     * Returns the response body from the API call.
     *
     * @return mixed The response body from the API call.
     */
    public function concertJson()
    {
        return json_decode($this->response);
    }


    /**
     * Throws an exception with the error message from the API call.
     *
     * @param Exception $ex The exception object.
     */
    public function exception(string $message)
    {
        throw new Exception($message);
    }
}
