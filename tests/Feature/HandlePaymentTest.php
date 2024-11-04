<?php

namespace Eboseogbidi\Smartpaymentrouter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Eboseogbidi\Smartpaymentrouter\Processors\StripeProcessor;
use Eboseogbidi\Smartpaymentrouter\Services\PaymentRouter;
use Eboseogbidi\Smartpaymentrouter\Tests\Integration\IntegrationTestCase;

class HandlePaymentTest extends IntegrationTestCase
{
    private PaymentRouter $paymentRouter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRouter = new PaymentRouter();
    }

    /** @test */
    public function it_handles_smart_payment_routing()
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

        // Create a mock for the stripe processor
        $processorMock = $this->createMock(StripeProcessor::class);
        $processorMock->method('getName')->willReturn('Stripe');
        $processorMock->method('processPayment')->with($paymentData)->willReturn(['status' => 'success']);
        $processorMock->method('callback')->willReturn($paymentData);

        // Assume selectBestProcessor returns the stripe processor
        $this->paymentRouter = $this->getMockBuilder(PaymentRouter::class)
            ->setMethods(['selectBestProcessor'])
            ->getMock();

        $this->paymentRouter->method('selectBestProcessor')->willReturn($processorMock);

        $result = $this->paymentRouter->routeTransaction($paymentData);

        $this->assertEquals($paymentData, $result);
        $this->assertNotEmpty($result); /
    }
}