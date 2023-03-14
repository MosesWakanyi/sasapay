<?php

namespace Mosesgathecha\Sasapay\Contracts;


/**
 * The SasapayCommunication interface defines the methods for communicating with a Sasapay system.
 */
/* Defining the methods that will be used to communicate with the Sasapay system. */
interface SasapayPayable
{
    /**
     * Returns the request body for the API call.
     *
     * @return mixed The request body for the API call.
     */
    public function businessToBusiness(array $params);

    /* A method that is used to communicate with the Sasapay system. */
    public function businessToCustomer(array $params);

    /* A method that is used to communicate with the Sasapay system. */
    public function customerToBusiness(array $params);

    /* A method that is used to approve a transaction. */
    public function customerToBusinessApprove(array $params);

    /* A method that is used to verify a transaction. */
    public function verifyTransaction(array $params);

    /* A method that is used to query the status of a transaction. */
    public function trxnStatus(array $params);

    /* A method that is used to query the balance of a merchant. */
    public function queryMerchantBalance(string $merchant_code);
}
