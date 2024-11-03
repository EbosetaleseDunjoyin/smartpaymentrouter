<?php 

namespace Eboseogbidi\Smartpaymentrouter\Processors;

use Eboseogbidi\Smartpaymentrouter\Contracts\PaymentProcessorInterface;
use Eboseogbidi\Smartpaymentrouter\Traits\DefaultCallbackTrait;


class StripeProcessor implements PaymentProcessorInterface{

    use DefaultCallbackTrait;

    public function getName():string{
        return 'Stripe';
    }

    public function processPayment(array $paymentData): array{

        //process stripe payment
        return $paymentData;
    }

    public function callback($response){
        return back()->with('success','Works fine');
    }


}
