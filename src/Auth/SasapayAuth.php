<?php

namespace Mosesgathecha\Sasapay\Auth;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use Mosesgathecha\Sasapay\Auth\SasapayBase;


/**
 * Class Authenticator
 *
 */
class SasapayAuth
{

    /**
     * @var string
     */
    protected $endpoint;
    /** 
     *@var SasapayBase
     */
    protected  $sasapayBase;
    /**
     * @var bool
     */
    /**
     * @var string
     */
    private $credentials;


    public function __construct(SasapayBase $sasapayBase)
    {
        $this->sasapayBase = $sasapayBase;
        $this->genCredential();
    }
    /**
     * @param bool $bulk
     * @return string
     */
    public function authenticate()
    {
        $token = $this->getToken();
        if (!$token) {
            return $this->AuthRequest();
        }
        return $token;
    }

    /**
     * @param $reason
     */
    private function generateException($reason)
    {
        switch (strtolower($reason)) {
            case 'bad request: invalid credentials':
                return new Exception('Invalid consumer key and secret combination');
            default:
                return new Exception($reason);
        }
    }

    /**
     * @return $this
     */
    private function genCredential()
    {
        $this->credentials = base64_encode(config('sasapay.client_id') . ':' . config('sasapay.client_secret'));
        return $this;
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function AuthRequest()
    {
        try {
            $response = $this->sasapayBase->client->request(
                'GET',
                $this->sasapayBase->baseUrl . '/auth/token/?grant_type=client_credentials',
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->credentials,
                    ],
                ]
            );
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody());
                $token = $data->access_token;
                $this->saveToken($token, $data->expires_in);
                return $this->getToken();
            } else {
                throw new Exception('Failed to return token');
            }
        } catch (RequestException $exception) {
            throw $this->generateException($exception->getResponse()->getReasonPhrase());
        }
    }

    /**
     * @return mixed
     */
    private function getToken()
    {
        return Cache::get($this->credentials);
    }

    /**
     * Store the credentials in the cache.
     *
     * @param $credentials
     */
    private function saveToken($token, $expireAt)
    {
        Cache::put($this->credentials, $token, $expireAt);
    }
}
