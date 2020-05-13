<?php

namespace App\Services;

use App\Models\Profile;
use App\Jobs\BankTransfer;
use App\Models\Transaction;
use App\Services\PaySwitchPayment;
use App\Aggregates\AccountAggregate;
use App\Notifications\MoMoPayOutFailed;
use App\Notifications\MoMoPayOutSuccess;



class PaymentService {


    /**
     * Tranfer To MobileMoney
     *
     * @param Array $request
     * @return array
     */
    public function floatToMobileMoney(Profile $profile, Transaction $transaction)
    {
        \Log::info('Started');
        $pay = new PaySwitchPayment();
        $pay->amount = $transaction->amount;
        $pay->account_number = $profile->momo_number;
        $pay->desc = config('app.name') . " Withdrawal Transaction"; 
        $pay->processing_code = "404010";
        $pay->account_issuer = $profile->momo_network;
        $pay->r_switch = "FLT";
        $pay->currency = "USD";

        $pay->momoTransfer();

        $user = $profile->user;

        if($pay->response_code == '000' && $pay->response_status == 'Approved'){
            $transaction->status = 'success';
            $transaction->description = 'Withdrawal was successful';
            $transaction->save();

            $user->notify( new MoMoPayOutSuccess($transaction));
        }
        else
        {
            $transaction->status = 'failed';
            $transaction->description = 'Withdrawal Failed';
            $transaction->save();

            // $user = $profile->user()->with('account')->first();


            $aggregateRoot = AccountAggregate::retrieve($user->account->uuid);
            $aggregateRoot->addMoney($transaction->amount);
            $aggregateRoot->persist(); 

            $user->notify( new MoMoPayOutFailed($transaction));
        }


    }


     /**
     * Tranfer from float To Bank
     *
     * @param Array $request
     * @return array
     */
    public function floatToBank(Array $request)
    {
        $pay = new PaySwitchPayment();
        $pay->amount = $request['amount'];
        $pay->account_number = $request['account_number'];
        if(isset($request['company_name'])){
            $pay->desc = $request['company_name'] . " Bank Transfer";
        }else{
            $pay->desc = "Bank Transfer";
        }
        $pay->processing_code = "404020";
        $pay->account_issuer = $request['account_issuer'];
        $pay->account_bank = $request['account_bank'];
        $pay->r_switch = "FLT";

        $pay->bankTransfer();

        if($pay->response_code == '000'){
            $array = array('transaction_id' => $pay->transaction_id, 'status'=> 'Successful', 'method'=> 'Float to Bank');
            $request = array_merge($request, $array);
            BankTransfer::dispatch($request)->onQueue('momo');
        }

        return $pay->response;
    }

     /**
     * Tranfer To Bank
     *
     * @param Array $request
     * @return array
     */
    public function authorizeTranferToBank(Array $request)
    {
        $pay = new PaySwitchPayment();
        $pay->reference_id = $request['reference_id'];

        $pay->authorizeBankTransfer();

        if($pay->response_code == '000' && $pay->response_status == 'Approved'){
            $request = array_add($request, 'status', 'Approved');
            BankTransfer::dispatch($request)->onQueue('momo');
        }

        return $pay->response;

    }

}
