<?php

namespace App\Patterns\MethodResponses;

use App\Patterns\Log\LogMessage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class AbstractMethodResponse
 */
abstract class AbstractMethodResponse
{
    use WithMethodResponseMessages;

    protected const ERROR_STATUS_TYPE_ID = 500;
    protected const INVALID_REQUEST_STATUS_TYPE_ID = 422;
    protected int $statusTypeId;

    protected static ?AbstractMethodResponse $errorInstance = null;
    protected static ?AbstractMethodResponse $invalidRequestInstance = null;

    /**
     * @param int $statusTypeId
     * @param array $messages
     */
    protected function __construct(int $statusTypeId, array $messages = []) {
        $this->statusTypeId = $statusTypeId;
        $this->setMessages($messages);
    }

    protected abstract function isSuccess(): bool;

    public abstract function __toString(): string;

    /**
     * @return int
     */
    public function getStatusTypeId(): int
    {
        return $this->statusTypeId;
    }

    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }

    /**
     * @param LogMessage[] $logMessages
     * @return static
     */
    public static function error(array $logMessages = []): static
    {
        foreach ($logMessages as $logMessage) {
            Log::error($logMessage->getMessage(), $logMessage->getContext());
        }

        return static::$errorInstance = new static(static::ERROR_STATUS_TYPE_ID);
    }

    public function isError(): bool
    {
        return static::ERROR_STATUS_TYPE_ID === $this->statusTypeId;
    }

    public function isInvalid(): bool
    {
        return static::INVALID_REQUEST_STATUS_TYPE_ID === $this->statusTypeId;
    }

    /**
     * @param array|string|null $messages
     *
     * @return static
     */
    public static function invalid(string|array|null $messages): static
    {
        if (empty($messages)) {
            return static::$invalidRequestInstance ??= new static(static::INVALID_REQUEST_STATUS_TYPE_ID);
        }

        if (is_string($messages)) {
            $messages = [new TranslatableMessage($messages)];
        }

        return static::$invalidRequestInstance = new static(
            static::INVALID_REQUEST_STATUS_TYPE_ID,
            $messages
        );
    }
}
