<?php

namespace Eboseogbidi\Smartpaymentrouter\Tests;

use \Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     // Add any setup code needed for all tests
    // }

    protected function getPackageProviders($app)
    {
        return [
            \Eboseogbidi\Smartpaymentrouter\Providers\SmartPaymentRouterProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set up any environment configuration
        $app['config']->set('smartpaymentrouter.processors', [
            'stripe' => [
                'name' => "Stripe",
                'class' => \Eboseogbidi\Smartpaymentrouter\Processors\StripeProcessor::class,
                'transaction_cost' => 1.8,
                'reliability' => 95,
                'currencies' => ['USD', 'GBP'],
            ],
        ]);
    }
}