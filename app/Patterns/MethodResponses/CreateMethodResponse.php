<?php

namespace App\Patterns\MethodResponses;

/**
 * Class CreateMethodResponse
 */
class CreateMethodResponse extends AbstractMethodResponse
{
    protected const CREATED_STATUS_TYPE_ID = 201;

    private static ?CreateMethodResponse $createdInstance = null;

    protected function isSuccess(): bool
    {
        return $this->isCreated();
    }

    public function isCreated(): bool
    {
        return static::CREATED_STATUS_TYPE_ID === $this->statusTypeId;
    }

    public static function created(): CreateMethodResponse
    {
        if (static::$createdInstance) {
            return static::$createdInstance;
        }
        return static::$createdInstance = new CreateMethodResponse(static::CREATED_STATUS_TYPE_ID);
    }

    public function __toString(): string
    {
        if (static::isCreated()) {
            return 'CREATED';
        }
        if (static::isInvalid()) {
            return 'CREATE_INVALID';
        }
        return 'CREATE_ERROR';
    }
}
