<?php

return [
    'routing_rules' => [
        'active' => true,
        'transaction_cost' => true,
        'reliability' => true,
        'currency_support' => true,
    ],

    'processors' => [
        'stripe' => [
            'name' => "Stripe",
            'class' => \Eboseogbidi\Smartpaymentrouter\Processors\StripeProcessor::class,
            'transaction_cost' => 1.8,
            'reliability' => 95,
            'currencies' => ['USD', 'GBP'],
            'active' => true,
        ],
    ],
];