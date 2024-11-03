<?php

namespace Eboseogbidi\Smartpaymentrouter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Eboseogbidi\Smartpaymentrouter\Processors\StripeProcessor;

class StripeProcessorTest extends TestCase
{
    private StripeProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = new StripeProcessor();
    }

    /** @test */
    public function it_validates_payment_data()
    {
        $paymentData = [
            'amount' => 100,
            'currency' => 'USD',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 12,
                'exp_year' => 2025,
                'cvc' => '123'
            ]
        ];

        $result = $this->processor->processPayment($paymentData);

        $this->assertEquals($result, $paymentData);
    
    }
}