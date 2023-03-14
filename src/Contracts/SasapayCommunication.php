<?php

namespace Mosesgathecha\Sasapay\Contracts;


/**
 * The SasapayCommunication interface defines the methods for communicating with a Sasapay system.
 */
interface SasapayCommunication
{
    /**
     * Returns the request body for the API call.
     *
     * @return mixed The request body for the API call.
     */
    public function setBody(string $params);

    /**
     * Returns the response body from the API call.
     *
     * @return mixed The response body from the API call.
     */
    public function concertJson();

    /**
     * Returns the setUrl for the API call.
     *
     * @return mixed The  setUrl for the API call.
     */
    public function setUrl(string $url);
    /**
     * Executes the API call with the specified parameters.
     *
     * @return mixed The response from the API call or an error message.
     */
    public function execute($method = '');

    /**
     * Returns the exception, if any, from the API call.
     *
     * @return mixed The exception from the API call or null if there is no exception.
     */
    public function exception(string $ex);
}
