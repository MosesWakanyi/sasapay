<?php

namespace Mosesgathecha\Sasapay\Transactions;


use Exception;
use Illuminate\Support\Facades\Validator;
use Mosesgathecha\Sasapay\Contracts\SasapayPayable;
use Mosesgathecha\Sasapay\ApiRequest\SasapayRequest;

/* Extending the `SasapayRequest` class and implementing the `SasapayPayable` interface. */

class SasapayTransaction extends SasapayRequest implements SasapayPayable
{
    /**
     * Returns the request body for the API call.
     *
     * @return mixed The request body for the API call.
     */
    /**
     * A function that is used to make a business to business payment.
     * 
     * @param array params 
     * 
     * @return The response from the API.
     */
    public function businessToBusiness(array $params)
    {

        $validator = Validator::make($params, [
            'merchant_code' => 'required',
            'transaction_ref' => 'required',
            'trxn_amount' => 'required|numeric',
            'receiver_merchant_code' => 'required'
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->all()));
        }
        $reqBody = [
            'MerchantCode' => $params['merchant_code'],
            'MerchantTransactionReference' =>  $params['transaction_ref'],
            'Amount' =>  $params['trxn_amount'],
            'ReceiverMerchantCode' => $params['receiver_merchant_code'],
            'Currency' =>  isset($params['trxn_currency']) ? $params['trxn_currency'] : 'KES',
            'Reason' => isset($params['payment_decription']) ? $params['payment_decription'] : "Remittanace",
            'CallBackURL' => isset($params['call_back_url']) ? $params['call_back_url'] : config('sasapay.callbacks') . 'b2b',
        ];
        return $this->setUrl('payments/b2b/')
            ->setBody($reqBody)
            ->execute();
    }
    /* A method that is used to communicate with the Sasapay system. */
    /**
     * A function that allows you to send money from your business account to a customer account.
     * 
     * @param array params 
     * 
     * @return The response from the API.
     */
    public function businessToCustomer(array $params)
    {
        $validator = Validator::make($params, [
            'merchant_code' => 'required',
            'transaction_ref' => 'required',
            'trxn_amount' => 'required|numeric',
            'receiver_number' => 'required'
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->all()));
        }
        $reqBody = [
            'MerchantCode' => strval($params['merchant_code']),
            'MerchantTransactionReference' =>  $params['transaction_ref'],
            'Amount' =>  $params['trxn_amount'],
            'ReceiverNumber' => $params['receiver_number'],
            'Currency' =>  isset($params['trxn_currency']) ? $params['trxn_currency'] : 'KES',
            'Channel' =>   isset($params['sender_channel']) ? $params['sender_channel'] : '0',
            'Reason' => isset($params['payment_decription']) ? $params['payment_decription'] : "Remittanace",
            'CallBackURL' => isset($params['call_back_url']) ? $params['call_back_url'] : config('sasapay.callbacks') . 'b2c'
        ];
        return $this->setUrl('/payments/b2c/')
            ->setBody($reqBody)
            ->execute();
    }
    /**
     * A function that is used to communicate with the Sasapay system.
     * 
     * @param array params 
     * 
     * @return The response from the Sasapay system.
     */
    /* A method that is used to communicate with the Sasapay system. */
    public function customerToBusiness(array $params)
    {

        $validator = Validator::make($params, [
            'merchant_code' => 'required',
            'account_ref' => 'required',
            'trxn_amount' => 'required|numeric',
            'phone_number' => 'required'
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->all()));
        }
        $reqBody = [
            'MerchantCode' => strval($params['merchant_code']),
            'AccountReference' =>  $params['account_ref'],
            'Amount' =>  $params['trxn_amount'],
            'TransactionFee' => isset($params['trxn_fee']) ?? 0,
            'PhoneNumber' => $params['phone_number'],
            'Currency' =>  isset($params['trxn_currency']) ?? 'KES',
            'NetworkCode' =>   isset($params['network_code']) ?? '0',
            'TransactionDesc' => isset($params['trxn_description']) ?? "Payments/Remittanace",
            'CallBackURL' => isset($params['call_back_url']) ? $params['call_back_url'] : config('sasapay.callbacks') . 'c2b',
        ];
        return $this->setUrl('/payments/request-payment/')
            ->setBody($reqBody)
            ->execute();
    }

    /**
     * It approves the payment request.
     * 
     * @param array params 
     * 
     * @return The response is a JSON object containing the following keys:
     */
    public function customerToBusinessApprove(array $params)
    {
        $validator = Validator::make($params, [
            'merchant_code' => 'required',
            'checkout_request_id' => 'required',
            'verify_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->all()));
        }
        $reqBody = [
            'MerchantCode' => strval($params['merchant_code']),
            'CheckoutRequestID' =>  $params['checkout_request_id'],
            'VerificationCode' =>  $params['verify_code'],
        ];
        return $this->setUrl('/payments/process-payment/')
            ->setBody($reqBody)
            ->execute();
    }
    /**
     * > This function verifies a transaction
     * 
     * @param array params 
     * 
     * @return The response from the API.
     */
    public function verifyTransaction(array $params)
    {
        $validator = Validator::make($params, [
            'merchant_code' => 'required',
            'transaction_code' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->all()));
        }
        $reqBody = [
            'MerchantCode' => strval($params['merchant_code']),
            'TransactionCode' =>  $params['transaction_code']
        ];
        return $this->setUrl('/transactions/verify/')
            ->setBody($reqBody)
            ->execute();
    }

    /**
     * It returns the status of a transaction.
     * 
     * @param array params 
     * 
     * @return The response is a JSON object with the following keys:
     */
    public function trxnStatus(array $params)
    {

        $validator = Validator::make($params, [
            'merchant_code' => 'required',
            'checkout_request_id' => 'required',
        ]);
        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->all()));
        }
        $reqBody = [
            'MerchantCode' => strval($params['merchant_code']),
            'CheckoutRequestId' =>  $params['checkout_request_id']
        ];
        return $this->setUrl('/transactions/status/')
            ->setBody($reqBody)
            ->execute();
    }

    /**
     * It checks the balance of a merchant.
     * 
     * @param string merchant_code The merchant code of the merchant you want to check the balance of.
     * 
     * @return The response is a JSON object with the following properties:
     */
    public function queryMerchantBalance(string $merchant_code)
    {

        if (empty($merchant_code)) {
            throw new Exception("The merchant code must be provided");
        }
        return $this->setUrl('payments/check-balance/?MerchantCode=' . strval($merchant_code))
            ->setBody([])
            ->execute('GET');
    }
}
