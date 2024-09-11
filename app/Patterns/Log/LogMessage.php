<?php

namespace App\Patterns\Log;

/**
 * Class LogMessage
 */
class LogMessage
{
    private string $message;
    private array $context;

    public function __construct(string $message)
    {
        $this->message = $message;
        $this->context = [];
    }

    public function addContext(string $key, mixed $value): static
    {
        $this->context[$key] = $value;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }

}
