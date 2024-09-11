<?php

namespace App\Exceptions;

use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\AbstractMethodResponse;
use Exception;
use Throwable;

/**
 * Class ServiceException
 * @package App\Exceptions
 */
class ServiceException extends Exception
{
    private array $messages;

    public function __construct(string|AbstractMethodResponse $message = '', int $code = 0, Throwable $previous = null, array $messages = [])
    {
        parent::__construct($message, $code, $previous);
        $this->formatMessage($message);
        $this->message = "$message";
        $this->code = $code;
        $this->messages = $messages;
    }

    private function formatMessage(&$message): void
    {
        try {
            $message = sprintf("%s->%s Returned: %s", $this->getTrace()[0]["class"], $this->getTrace()[0]["function"], $message);
        } catch (Exception $exception) {

        }
    }

    /**
     * @return bool
     */
    public function hasMessages(): bool
    {
        return !empty($this->messages);
    }

    /**
     * @return LogMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function setMessages(array $messages): static
    {
        $this->messages = $messages;
        return $this;
    }
}
