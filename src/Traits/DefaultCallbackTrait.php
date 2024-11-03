<?php


namespace Eboseogbidi\Smartpaymentrouter\Traits;

trait DefaultCallbackTrait
{
    /**
     * Default callback handler if no custom callback is provided.
     *
     * @param array $response
     * @return mixed
     */
    public function callback(array $response)
    {
        // Default callback implementation
        return [
            'status' => $response['status'] ?? 'unknown',
            'message' => $response['message'] ?? 'No message provided',
        ];
    }
}