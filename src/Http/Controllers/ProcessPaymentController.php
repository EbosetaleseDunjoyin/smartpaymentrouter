<?php

namespace Eboseogbidi\Smartpaymentrouter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Eboseogbidi\Smartpaymentrouter\Services\PaymentRouter;
use Eboseogbidi\Smartpaymentrouter\Services\ProcessorManager;
use Illuminate\Http\JsonResponse;

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
    /**
     * This is to test the process manager
     *
     * @return JsonResponse
     */
    public function testProcessManager(): JsonResponse
    {
        
        $configRepository = new Repository();

        
        $manager = new ProcessorManager();

        $manager->addProcessor('newssss', [
            'api_key' => 'new_key',
            'secret' => 'new_secret',
        ]);

        
        $processors = $manager->getProcessors();

        return response()->json($processors);
    }


}
