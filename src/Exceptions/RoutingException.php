<?php

namespace Eboseogbidi\Smartpaymentrouter\Exceptions;

use Exception;

class RoutingException extends Exception
{
    protected $message = 'Error occurred during payment processing.';
}