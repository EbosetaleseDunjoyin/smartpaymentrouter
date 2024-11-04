# SmartPaymentRouter

A flexible and intelligent payment routing system that helps you manage multiple payment processors with automatic fallback and smart routing based on cost, reliability, and supported currencies.

## Installation

You can install the package via composer:

```bash
composer require eboseogbidi/smartpaymentrouter
```


## Migration

Migrate the schema for the payment transaction log this helps to improve the reliability of the different payment processors:

```bash
php artisan migrate
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=smartpaymentrouter-config
```
This will create a `config/smartpaymentrouter.php` file where you can configure your payment processors.

### Basic Configuration Structure

```php
return [
    'routing_rules' => [
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
        ],
        // Add more processors here
    ],
];
```

Configuration parameters:
- `name`: Display name for the processor
- `class`: The processor class that implements PaymentProcessorInterface
- `transaction_cost`: Processing fee percentage
- `reliability`: Estimated uptime percentage
- `currencies`: Array of supported currencies

## Extending PaymentProcessorInterface

To create a new payment processor, implement the `PaymentProcessorInterface`:

```php
use Eboseogbidi\Smartpaymentrouter\Interfaces\PaymentProcessorInterface;

class StripeProcessor implements PaymentProcessorInterface{

    //If you want to extend the callback functionality
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
```

## Using the Payment Router

Example of how to use the `PaymentRouter` in a Controller :

```php
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
```


## Using the ProcessorManager

The ProcessorManager provides methods to interact with and modify the payment configuration:

```php
use Eboseogbidi\Smartpaymentrouter\Services\ProcessorManager;

class PaymentService
{

    public function __construct(protected ProcessorManager $processorManager){}

    public function updateProcessorConfig()
    {
        // Add new processor
        $this->processorManager->addProcessor('paypal', [
            'name' => 'PayPal',
            'class' => PayPalProcessor::class,
            'transaction_cost' => 2.0,
            'reliability' => 98,
            'currencies' => ['USD', 'EUR'],
        ]);

        // Update existing processor
        $this->processorManager->updateProcessor('stripe', [
            'transaction_cost' => 1.9,
            'reliability' => 97,
        ]);

        // Remove processor
        $this->processorManager->removeProcessor('square');

        //Get Processors
        $this->processorManager->getProcessors();
        
    }
}
```

## Testing

The package includes a comprehensive test suite. Run the tests with:

```bash
composer test
```


## Error Handling

The package provides built-in error handling for common scenarios:

```php
try {
    $result = $processor->processPayment($paymentData);
} catch (PaymentProcessingException $e) {
    // Handle processor-specific errors
    Log::error('Payment processing failed: ' . $e->getMessage());
} catch (RoutingException $e) {
    // Handle processor-specific errors
    Log::error('Routing error failed: ' . $e->getMessage());
} catch (ValidationException $e) {
    // Handle validation errors
    $errors = $e->getValidationErrors();
}
```

