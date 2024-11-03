<?php

namespace Eboseogbidi\Smartpaymentrouter\Contracts;

interface PaymentProcessorInterface
{
    /**
     * Get the name of the payment processor.
     *
     * @return string
     */
    public function getName(): string;
    
    /**
     * Process the payment with the provided payment data.
     *
     * @param array $paymentData
     * @return array
     */
    public function processPayment(array $paymentData): array;



    /**
     * Handle the callback after processing payment.
     *
     * @param array $response
     * @return mixed
     */
    public function callback(array $response);
}