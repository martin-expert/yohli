<?php

namespace App\Services;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;


/**
 * PaySwitchPayment Service Implementation.
 */
Class PaySwitchPayment {

    /**
     * Base url
     *
     * @var string
     * @property required
     */
    public $base_uri;

    /**
     * Instance of Guzzele client
     *
     * @var object
     */
    public $http;

    /**
     * Your API key provided when you create an account.
     *
     * @var string
     * @property required
     */
    private $api_key;

 /**
     * Your username provided used to create an account.
     *
     * @var string
     * @property required
     */
    private $username;

 /**
     * Your merchant API key provided when you create an account.
     *
     * @var string
     * @property required
     */
    private $merchant_id;

    /**
     * Unique transaction reference provided by you
     *
     * @property required
     * @var string
     */
    public $transaction_id;

    /**
     * Amount to charge.
     *
     * @var string
     * @property required
     */
    public $amount;

    /**
     * This is a transaction type identifier.
     * "404000" is default for transfer to mobile money,
     * "404020" for bank transfer,
     * "000000" for card payment,
     * "000200" for mobile money payment
     *
     * @var string
     * @property required
     */
    public $processing_code;

    /**
     * Account issuer or network on which the account to be debited resides.
     * default is "FLT" for float.
     *
     * @var string
     * @property required
     */
    public $r_switch;

    /**
     * Voucher code for vodafone users.
     *
     * @var string
     * @property required
     */
    public $voucher_code;

    /**
     * Text to be displayed as a short transaction narration.
     *
     * @var string
     * @property reuired
     */
    public $desc;

    /**
     * Unique pass code generated when you create a float account.
     *
     * @var string
     * @property required
     */
    public $pass_code;

    /**
     * Recipient account number or wallet number for transfer transaction.
     *
     * @var string
     * @property required
     */
    public $account_number;

      /**
     * The network the account belongs to. e.g #endregion
     * "MTN" for MTN
     * "ATL" for Airtel
     * "VDF" for Vodafone
     * "TGO" for Tigo
     * "GIP" for bank account transfer
     *
     * @var string
     * @property required
     */
    public $account_issuer;

     /**
     * The recipient bank account, e.g "GCB" for Ghana commercial bank
     *
     * @var string
     * @property required
     * @link https://theteller.net/documentation#bank_list
     */
    public $account_bank;

    /**
     * Your customer email.
     *
     * @var string
     * @property required
     */
    public $customer_email;

    /**
     * Card holder name on card.
     *
     * @var string
     * @property required
     */
    public $card_holder;

    /**
     * Currency to charge card.
     *
     * @var string
     * @property required
     */
    public $currency;

    /**
     * Card pan number on card.
     *
     * @var string
     * @property required
     */
    public $pan;

    /**
     * Expiry month of card.
     *
     * @var string
     * @property required
     */
    public $exp_month;

    /**
     * Expiry year of card.
     *
     * @var string
     * @property required
     */
    public $exp_year;

    /**
     * CVV no back of card.
     *
     * @var string
     * @property required
     */
    public $cvv;

    /**
     * Callback url to return your user to when transaction is completed
     */
    public $third_url_response;

    /**
     * Mobile money wallet to charge
     *
     * @var string
     * @property required
     */
    public $subscriber_number;

     /**
     * Reference id recived from transfer to bank request
     *
     * @var string
     * @property required
     */
    public $reference_id;

    /**
     * Response Code from Api reuest
     *
     * @var string
     * @property No
     */
    public $response_code;

    /**
     * Response Reason from Api reuest
     *
     * @var string
     * @property No
     */
    public $response_reason;

     /**
     * Response status from Api reuest
     *
     * @var string
     * @property No
     */
    public $response_status;

    /**
     * Response from Api reuest
     *
     * @var string
     * @property No
     */
    public $response;

    public function __construct()
    {
        $this->merchant_id = config('payswitch.merchant_id');
        $this->api_key = config('payswitch.api_key');
        $this->pass_code = config('payswitch.pass_code');
        $this->username = config('payswitch.username');
        $this->base_uri = config('payswitch.base_uri', 'https://prod.theteller.net');
        $this->third_url_response = config('payswitch.redirect_url');
        $this->transaction_id = $this->unique_code();
        $this->http = $this->headers();
    }


    /**
     * Bank transfer
     *
     * @return array
     */
    public function bankTransfer()
    {
        $body = [
            'amount' => $this->serialize_amount($this->amount),
            'r-switch' => $this->r_switch,
            'transaction_id' => $this->transaction_id,
            'account_number' => $this->account_number,
            'desc' => $this->desc,
            'merchant_id' => $this->merchant_id,
            'processing_code' => $this->processing_code,
            'account_issuer' => $this->account_issuer,
            'account_bank' => $this->account_bank,
            'pass_code' => $this->pass_code,
        ];


        $response = $this->async_call(config('payswitch.payment_url'), $body);

        $response = [
            'request' => $body,
            'response' => $response
        ];
        Log::debug($response);
        return $response;

    }

    //  /**
    //  * Bank transfer
    //  *
    //  * @return array
    //  */
    // public function momoToBankTransfer()
    // {
    //     $body = [
    //         'amount' => $this->serialize_amount($this->amount),
    //         'r-switch' => $this->r_switch,
    //         'transaction_id' => $this->transaction_id,
    //         'account_number' => $this->account_number,
    //         // 'subscriber_number' => $this->subscriber_number,
    //         'desc' => $this->desc,
    //         'merchant_id' => $this->merchant_id,
    //         'processing_code' => $this->processing_code,
    //         'account_issuer' => $this->account_issuer,
    //         'account_bank' => $this->account_bank,
    //         'pass_code' => $this->pass_code,
    //     ];


    //     $response = $this->async_call(config('payswitch.payment_url'), $body);

    //     $response = [
    //         'request' => $body,
    //         'response' => $response
    //     ];
    //     Log::debug($response);
    //     return $response;

    // }

     /**
     * Bank transfer
     *
     * @return array
     */
    public function authorizeBankTransfer()
    {
        $body = [
            'merchant_id' => $this->merchant_id,
            'reference_id' => $this->reference_id,
        ];

        $response = $this->async_call(config('payswitch.bank_tranfer_authorize'), $body);

        $response = [
            'request' => $body,
            'response' => $response
        ];
        Log::debug($response);
        return $response;

    }

    /**
     * Mobile Money Transfer
     *
     * @return array
     */
    public function momoTransfer()
    {
        $body = [
            'amount' => $this->serialize_amount($this->amount),
            'r-switch' => $this->r_switch,
            'transaction_id' => $this->transaction_id,
            'account_number' => $this->account_number,
            'desc' => $this->desc,
            'merchant_id' => $this->merchant_id,
            'processing_code' => $this->processing_code,
            'account_issuer' => $this->account_issuer,
            'pass_code' => $this->pass_code,

        ];


        $response = $this->async_call(config('payswitch.payment_url'), $body);

        $response = [
            'request' => $body,
            'response' => $response
        ];
        Log::debug($response);
        return $response;
    }

   


    /**
     * new guzzle client with headers set
     *
     * @return Object
     */
    public function headers()
    {
        $http = new Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => ["Basic ".$this->credentials()],
                'Cache-Control' => 'no-cache',
                'Accept' => 'Accept: */*',
                'User-Agent' => 'guzzle/6.0',
                'Accept-Charset' => '*',
                'Accept-Encoding' => '*',
                'Accept-Ranges' => 'none',
                'Accept-Language' => '*',
        ]]);

        return $http;
    }

    /**
     * set authorization credential
     *
     * @return array
     */
    public function credentials()
    {
        $credentials = base64_encode($this->username. ':' .$this->api_key);

        return $credentials;
    }

    /**
     * sents the request to api service
     *
     * @param string $uri
     * @param string $body
     * @return json
     */
    public function async_call($uri, $body)
    {
        Log::info([$uri, $body]);
        $makePaymentPromise = $this->http->postAsync($uri, ["body" => json_encode($body)])->then(
            function(ResponseInterface $response){
                return $response;
            },
                function(RequestException $e){
                    $message =  $e->getMessage() . "\n";
                    Log::critical($message);
                    return $e;
                }
            );


            $response = $makePaymentPromise->wait();

            if($response instanceof RequestException)
            {
                $response = (string) $response->getResponse()->getBody();
                $response = json_decode($response, true);
                return $this->response =  [
                    'request' => $body,
                    'response' => $response
                ];;
            }

            $response = json_decode($response->getBody()->getContents(), true);
            $this->response_status = $response['status'];
            $this->response_code = $response['code'];
            if(isset($response['reason'])){
                $this->response_reason = $response['reason'];
            }
            if(isset($response['description'])){
                $this->response_reason = $response['description'];
            }
            return $this->response =  [
                'request' => $body,
                'response' => $response
            ];
    }

    /**
     * generates a 12 random digit number.
     *
     * @return init
     */
    public function unique_code()
    {
        $milliseconds = (String) round(microtime(true) * 568);
        $shuffled = str_shuffle($milliseconds);
        $id = substr($shuffled, 0, 12);
        return $id;
    }
    /**
     * serialize amount to a 12 digit bigInteger
     *
     * @param [type] $amount
     * @return void
     */
    public function serialize_amount($amount)
    {
       return sprintf("%'.012d", $amount*100);
    }


}
