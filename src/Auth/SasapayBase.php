<?php

namespace Mosesgathecha\Sasapay\Auth;

use GuzzleHttp\ClientInterface;

/**
 * Class WageBase
 *
 */
class SasapayBase
{
    /**
     * @var ClientInterface
     */
    public $client;
    /**
     * @var SasapayAuth API
     */
    public $sasapayAuth;

    public $baseUrl = 'https://sandbox.sasapay.app/api/v1';

    /**
     * WageBase constructor.
     *
     * @param  ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->sasapayAuth = new SasapayAuth($this);
    }
}
