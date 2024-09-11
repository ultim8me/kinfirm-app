<?php

namespace App\Patterns\MethodResponses;

use App\Patterns\Log\LogMessage;

/**
 * Class DeleteMethodResponse
 */
class DeleteMethodResponse extends AbstractMethodResponse
{
    protected const DELETED_STATUS_TYPE_ID = 200;

    private static ?DeleteMethodResponse $deletedInstance = null;

    public static function deleted(): DeleteMethodResponse
    {
        if (self::$deletedInstance) {
            return self::$deletedInstance;
        }
        return self::$deletedInstance = new DeleteMethodResponse(static::DELETED_STATUS_TYPE_ID);
    }

    public function __toString(): string
    {
        if (static::isDeleted()) {
            return 'DELETED';
        }
        if (static::isInvalid()) {
            return 'DELETE_INVALID';
        }
        return 'DELETE_ERROR';
    }

    protected function isSuccess(): bool
    {
        return $this->isDeleted();
    }

    public function isDeleted(): bool
    {
        return static::DELETED_STATUS_TYPE_ID === $this->statusTypeId;
    }
}
