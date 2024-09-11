<?php

namespace App\Patterns\MethodResponses;

/**
 * Class FetchMethodResponse
 */
class FetchMethodResponse extends AbstractMethodResponse
{
    protected const FOUND_STATUS_TYPE_ID = 200;
    protected const NOT_FOUND_STATUS_TYPE_ID = 201;

    private static ?FetchMethodResponse $foundInstance = null;
    private static ?FetchMethodResponse $notFoundInstance = null;

    public static function found(): static
    {
        if (self::$foundInstance) {
            return self::$foundInstance;
        }
        return self::$foundInstance = new static(static::FOUND_STATUS_TYPE_ID);
    }

    public static function notFound(): static
    {
        if (self::$notFoundInstance) {
            return self::$notFoundInstance;
        }
        return self::$notFoundInstance = new static(static::NOT_FOUND_STATUS_TYPE_ID);
    }

    public function __toString(): string
    {
        if (static::isFound()) {
            return 'FETCH_FOUND';
        }
        if (static::isNotFound()) {
            return 'FETCH_NOT_FOUND';
        }
        if (static::isInvalid()) {
            return 'FETCH_INVALID';
        }
        return 'FETCH_ERROR';
    }

    public function isNotFound(): bool
    {
        return static::NOT_FOUND_STATUS_TYPE_ID === $this->statusTypeId;
    }

    protected function isSuccess(): bool
    {
        return $this->isFound();
    }

    public function isFound(): bool
    {
        return static::FOUND_STATUS_TYPE_ID === $this->statusTypeId;
    }
}
