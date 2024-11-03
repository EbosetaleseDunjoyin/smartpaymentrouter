<?php

namespace Eboseogbidi\Smartpaymentrouter\Http\Controllers;

use Illuminate\Http\Request;
use Eboseogbidi\Smartpaymentrouter\Services\PaymentRouter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class ProcessPaymentController extends BaseController
{
    
    public function __construct(protected PaymentRouter $paymentRouter){}
    

    /**
     * Handle the payment transaction.
     *
     * @param Request $request
     * @return mixed
     */
    public function handlePayment(Request $request): mixed
    {
       
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3', 
        ]);

        try {
            $paymentData = $request->all();

            $result = $this->paymentRouter->routeTransaction($paymentData);

           return $result;

        } catch (\Exception $e) {
            
            Log::error("Payment transaction error: {$e->getMessage()}");

            return back()->with('error','Transaction failed'. $e->getMessage());
        }
    }
}
