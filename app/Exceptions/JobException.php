<?php

namespace App\Exceptions;

use App\Patterns\MethodResponses\AbstractMethodResponse;
use Exception;
use Throwable;

/**
 * Class JobException
 * @package App\Exceptions
 */
class JobException extends Exception
{
    public function __construct(string|AbstractMethodResponse $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = "$message";
        $this->code = $code;
    }
}
