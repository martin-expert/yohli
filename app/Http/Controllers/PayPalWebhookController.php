<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;

class PayPalWebhookController extends Controller
{
 /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function __invoke(Request $request)
    {
        $paypal = new PayPalService();

        $paypal->validateWebhook($request);
        
    }
}
