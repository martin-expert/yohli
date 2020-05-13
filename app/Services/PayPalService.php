<?php

namespace App\Services;

use \PayPal\Api\Payout;
use App\Models\Profile;
use \PayPal\Api\Currency;
use \PayPal\Api\PayoutItem;
use App\Models\Transaction;
use \PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use \PayPal\Api\WebhookEvent;
use App\Aggregates\AccountAggregate;
use \PayPal\Auth\OAuthTokenCredential;
use \PayPal\Api\VerifyWebhookSignature;
use \PayPal\Api\PayoutSenderBatchHeader;
use App\Notifications\PayPalPayOutFailed;
use App\Notifications\PayPalPayOutSuccess;
use App\Notifications\PayPalPayOutProcessing;
use PayPal\Api\VerifyWebhookSignatureResponse;

class PayPalService {

    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;
    private $webhook_id;
    
    // Create a new instance with our paypal credentials
    public function __construct()
    {
        // Detect if we are running in live mode or sandbox
        if(config('paypal.settings.mode') == 'live'){
            $this->client_id = config('paypal.live_client_id');
            $this->secret = config('paypal.live_secret');
        } else {
            $this->client_id = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
        }
        $this->webhook_id = config('paypal.webhook_id');
        
        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
             $this->client_id,
             $this->secret
            )
        );
        $this->apiContext->setConfig(config('paypal.settings') );


    }

    public function singlePayout(Profile $profile, Transaction $transaction)
    {
        \Log::info('PayPal Started');

        $transaction_id = unique_code();
        $batch_id = uniqid();
        $transaction->transaction_id = $transaction_id;
        $transaction->batch_id = $batch_id;

        $payouts = new Payout();
        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId($batch_id)
                          ->setEmailSubject("Yohli Money Transfer");
        $senderItem = new PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for using Yohli!')
            ->setReceiver($profile->email)
            ->setSenderItemId($transaction_id)
            ->setAmount(new Currency(json_encode(
                [
                    'value' => $transaction->amount,
                    'currency' => 'USD'
                ]
            )));



            
        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);

        // $request = clone $payouts;

        $status = '';
        try {
            $output = $payouts->create(null, $this->apiContext);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            $transaction->status = "failed";
            $transaction->description = "Account withdrawal failed";
            $transaction->save();
            $account = $transaction->account()->with('user')->first();

            $aggregateRoot = AccountAggregate::retrieve($account->uuid);
            $aggregateRoot->addMoney($transaction->amount);
            $aggregateRoot->persist();

            $user = $account->user;
            $user->notify(new PayPalPayOutFailed($transaction));
            $status = "failed";
        }

        if($status == 'failed'){
            return;
        }

        
        // $transaction->batch_id = $output->getBatchHeader()->getPayoutBatchId();
        $account = $transaction->account()->with('user')->first();
        $user = $account->user;
        $transaction->description = "Account withdrawal processing";
        $transaction->save();
        $user->notify(new PayPalPayOutProcessing($transaction));
        // \ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
        \Log::info(print_r($output, true));
        // return $output;

    }

    public function validateWebhook(Request $request)
    {
        // Get request details
        $requestBody = $request->getContent();
        $headers = array_change_key_case($request->headers->all(), CASE_UPPER);
        
        $signatureVerification = new VerifyWebhookSignature();
        $signatureVerification->setAuthAlgo($request->header("paypal-auth-algo"));
        $signatureVerification->setTransmissionId($request->header("paypal-transmission-id"));
        $signatureVerification->setCertUrl($request->header("paypal-cert-url"));
        $signatureVerification->setWebhookId($this->webhook_id); 
        $signatureVerification->setTransmissionSig($request->header("paypal-transmission-sig"));
        $signatureVerification->setTransmissionTime($request->header("paypal-transmission-time"));
        
        $signatureVerification->setRequestBody($requestBody);
        // $req = clone $signatureVerification;
        
        try {
            /** @var \PayPal\Api\VerifyWebhookSignatureResponse $output */
            $output = $signatureVerification->post($this->apiContext);
        } catch (\Exception $e) {
            \Log::critical($e);
        }
        \Log::info(print_r($output, true));


        $status = $output->getVerificationStatus(); // 'SUCCESS' or 'FAILURE'
        \Log::critical($status);
        // $status =  'SUCCESS';

        switch(strtoupper($status)) {
            case "FAILURE":
                echo $status;
            case "SUCCESS":
                $json = json_decode($requestBody, 1);
                goto UPDATE_TRANSACTION;
            }
        
        UPDATE_TRANSACTION:
        switch($json['event_type']) {
            case "PAYMENT.PAYOUTS-ITEM.SUCCEEDED":
                $itemID = $json['resource']['payout_item']['sender_item_id'];
                $transaction = Transaction::where('transaction_id', $itemID)->first();
                // $transaction = Transaction::find(10);
                $transaction->status = 'success';
                $transaction->description = $json['resource']['transaction_status'];
                $transaction->save();

                $account = $transaction->account()->with('user')->first();
                $user = $account->user;

                $user->notify(new PayPalPayOutSuccess($transaction));
                \Log::info(print_r($user, true));
                break;

            case "PAYMENT.PAYOUTS-ITEM.UNCLAIMED":
                    $itemID = $json['resource']['payout_item']['sender_item_id'];
                    $transaction = Transaction::where('transaction_id', $itemID)->first();
                    // $transaction = Transaction::find(10);
                    $transaction->status = 'pending';
                    $transaction->description = $json['resource']['transaction_status'];
                    $transaction->save();
    
                    $account = $transaction->account()->with('user')->first();
                    $user = $account->user;
    
                    $user->notify(new PayPalPayOutProcessing($transaction));
                    \Log::info(print_r($user, true));
                    break;
            
            case (
                "PAYMENT.PAYOUTS-ITEM.REFUNDED" || "PAYMENT.PAYOUTS-ITEM.CANCELED" || 
                "PAYMENT.PAYOUTS-ITEM.DENIED" || "PAYMENT.PAYOUTS-ITEM.FAILED" || 
                "PAYMENT.PAYOUTS-ITEM.RETURNED" 
                ):
                $itemID = $json['resource']['payout_item']['sender_item_id'];
                $transaction = Transaction::where('transaction_id', $itemID)->first();
                $transaction->status = 'failed';
                $transaction->description = $json['resource']['transaction_status'];
                $transaction->save();

                $account = $transaction->account()->with('user')->first();

                $aggregateRoot = AccountAggregate::retrieve($account->uuid);
                $aggregateRoot->addMoney($transaction->amount);
                $aggregateRoot->persist(); 

                $user = $account->user;
                $user->notify(new PayPalPayOutFailed($transaction));
                \Log::info(print_r($user, true));
                break;

            default:
                
                break;

            
            // case "PAYMENT.PAYOUTSBATCH.BLOCKED":

            // case "PAYMENT.PAYOUTSBATCH.HELD":

            // case "PAYMENT.PAYOUTSBATCH.SUCCEESS":

            // case "PAYMENT.PAYOUTSBATCH.PROCESSING":

            // case "PAYMENT.PAYOUTSBATCH.DENIED":
               
        }

        
        echo $status;

    }
}