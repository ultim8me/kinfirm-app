<?php

namespace App\Patterns\Log;

/**
 * Class LogException
 */
class LogException extends LogMessage
{
    public function __construct(\Exception $exception)
    {
        parent::__construct(
            $exception->getMessage()
        );
    }
}
