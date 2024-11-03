<?php

namespace Eboseogbidi\Smartpaymentrouter\Exceptions;

use Exception;

class PaymentProcessingException extends Exception
{
    protected $message = 'Error occurred during payment processing.';
}