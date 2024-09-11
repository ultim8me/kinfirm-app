<?php

namespace App\Patterns\MethodResponses;

use App\Patterns\Log\LogMessage;

/**
 * Class UpdateMethodResponse
 */
class UpdateMethodResponse extends AbstractMethodResponse
{
    protected const UPDATED_STATUS_TYPE_ID = 200;
    protected const NOT_FOUND_STATUS_TYPE_ID = 404;

    private static ?UpdateMethodResponse $updatedInstance = null;
    private static ?UpdateMethodResponse $notFoundInstance = null;

    /**
     * @param FetchMethodResponse $methodResponse
     *
     * @return UpdateMethodResponse
     */
    public static function fromFetchMethodResponse(FetchMethodResponse $methodResponse): static
    {
        if ($methodResponse->isInvalid()) {
            return static::invalid($methodResponse->getMessages());
        }

        if ($methodResponse->isError()) {
            return static::error();
        }

        if ($methodResponse->isNotFound()) {
            return static::notFound();
        }

        return static::error([
            new LogMessage(sprintf(
                "Unable to map FetchMethodResponse of status \"%s\" to UpdateMethodResponse.",
                $methodResponse->__toString()
            )),
        ]);
    }

    public static function notFound(): UpdateMethodResponse
    {
        if (self::$notFoundInstance) {
            return self::$notFoundInstance;
        }
        return self::$notFoundInstance = new UpdateMethodResponse(static::NOT_FOUND_STATUS_TYPE_ID);
    }

    public static function updated(): UpdateMethodResponse
    {
        if (self::$updatedInstance) {
            return self::$updatedInstance;
        }
        return self::$updatedInstance = new UpdateMethodResponse(static::UPDATED_STATUS_TYPE_ID);
    }

    public function __toString(): string
    {
        if (static::isUpdated()) {
            return 'UPDATED';
        }
        if (static::isNotFound()) {
            return 'UPDATE_NOT_FOUND';
        }
        if (static::isInvalid()) {
            return 'UPDATE_INVALID';
        }
        return 'UPDATE_ERROR';
    }

    public function isNotFound(): bool
    {
        return static::NOT_FOUND_STATUS_TYPE_ID === $this->statusTypeId;
    }

    protected function isSuccess(): bool
    {
        return $this->isUpdated();
    }

    public function isUpdated(): bool
    {
        return static::UPDATED_STATUS_TYPE_ID === $this->statusTypeId;
    }

}
