<?php

namespace Eboseogbidi\Smartpaymentrouter\Services;

use Illuminate\Support\Facades\Log;
use Eboseogbidi\Smartpaymentrouter\Exceptions\RoutingException;
use Eboseogbidi\Smartpaymentrouter\Models\PaymentTransactionLog;
use Eboseogbidi\Smartpaymentrouter\Exceptions\PaymentProcessingException;

class PaymentRouter
{
    protected $processors;
    protected $rules;

    public function __construct()
    {
        $this->processors = config('smartpaymentrouter.processors');
        $this->rules = config('smartpaymentrouter.routing_rules');
    }

    /**
     * Route the transaction to the best processor and process payment.
     *
     * @param array $paymentData
     * @return mixed
     * @throws RoutingException|PaymentProcessingException
     */
    public function routeTransaction(array $paymentData)
    {
        try {
            // Select the best processor based on currency
            $processor = $this->selectBestProcessor($paymentData['currency']);
            Log::info("Selected payment processor: {$processor->getName()}", ['currency' => $paymentData['currency']]);

            // Process payment using the selected processor
            $result = $processor->processPayment($paymentData);

            // Log transaction success
            $this->logTransaction($processor->getName(),$paymentData['amount'], true);
            Log::info("Payment processed successfully - {$processor->getName()}", ['currency' => $paymentData['currency']]);

            return $processor->callback($result);

        } catch (RoutingException $e) {
            Log::error("Routing error: {$e->getMessage()}", ['currency' => $paymentData['currency']]);
            throw $e;

        } catch (PaymentProcessingException $e) {
            Log::error("Payment processing error: {$e->getMessage()}", ['currency' => $paymentData['currency']]);
            $this->logTransaction($processor->getName(),$paymentData['amount'], false);
            throw $e;
        }
    }

    /**
     * Select the best payment processor based on currency and dynamic factors.
     *
     * @param string $currency
     * @return mixed
     * @throws RoutingException
     */
    public function selectBestProcessor(string $currency)
    {
        $bestProcessor = null;
        $bestScore = -1;

        foreach ($this->processors as $processorKey => $details) {
            //skip in active processors
            if (\config('smartpaymentrouter.routing_rules.active', true) && (!isset($details['status']) || $details['status'] !== 'active')) {
                continue;
            }
            if (in_array($currency, $details['currencies'])) {
                $score = 0;

                

                if (\config('smartpaymentrouter.routing_rules.transaction_cost', true)) {
                    $score += 100 - $details['transaction_cost']; // Higher score for lower cost
                }

                if (\config('smartpaymentrouter.routing_rules.reliability', true)) {
                    $dynamicReliability = $this->getDynamicReliability($details['name']);
                    $score += $dynamicReliability; 
                }

                if (\config('smartpaymentrouter.routing_rules.currency_support', true)) {
                    $score += 50; 
                }

                if ($score > $bestScore) {
                    $bestProcessor = $processorKey;
                    $bestScore = $score;
                }
            }
        }

        if (!$bestProcessor) {
            throw new RoutingException(" No suitable payment processor found for currency: $currency");
        }

        return app($this->processors[$bestProcessor]['class']);
    }

    /**
     * Get the dynamic reliability score for a payment processor.
     * Adjust base reliability based on historical failure rates from transaction logs.
     *
     * @param string $processorName
     * @return float
     */
    protected function getDynamicReliability(string $processorName): float
    {
        // Base reliability from configuration
        $baseReliability = $this->processors[$processorName]['reliability'] ?? 100;

        try {
            
            $totalTransactions = PaymentTransactionLog::where('processor_name', $processorName)->count();
            $failedTransactions = PaymentTransactionLog::where('processor_name', $processorName)
                ->where('success', false)
                ->count();

            if ($totalTransactions === 0) {
                return $baseReliability; 
            }

            // Calculate dynamic reliability
            $failureRate = ($failedTransactions / $totalTransactions) * 100;
            $dynamicReliability = $baseReliability - $failureRate;

            // Ensure the reliability doesn't drop below a reasonable threshold (e.g., 50%)
            return max(50, $dynamicReliability);

        } catch (\Exception $e) {
            Log::error("Error calculating dynamic reliability for $processorName: {$e->getMessage()}");
            return $baseReliability; 
        }
    }

    /**
     * Log the result of a payment transaction.
     *
     * @param string $processorName
     * @param bool $success
     */
    public function logTransaction(string $processorName, float $amount, bool $success)
    {
        try {
            PaymentTransactionLog::create([
                'processor_name' => $processorName,
                'amount' => $amount,
                'success' => $success,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to log transaction for $processorName: {$e->getMessage()}");
        }
    }
}