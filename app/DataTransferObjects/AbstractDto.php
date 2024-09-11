<?php

namespace App\DataTransferObjects;

use DateTime;
use DateTimeZone;

/**
 * Class AbstractDto
 * @package App\DataTransferObjects
 */
abstract class AbstractDto
{
    public function toArray(bool $withNullValues = true): array
    {
        $values = get_object_vars($this);

        if (!$withNullValues) {
            $values = array_filter($values, fn ($value) => !is_null($value));
        }

        return array_filter($values, fn ($key) => !str_contains($key, "Dto"), ARRAY_FILTER_USE_KEY);
    }

    protected function getDateTime(DateTime|string|int|null $dateTime): ?DateTime
    {
        if (is_null($dateTime)) {
            return null;
        }

        if (is_int($dateTime)) {
            $dateTime = '@' . $dateTime;
        }

        if (is_string($dateTime)) {
            $dateTime = date_create($dateTime)->setTimezone(new DateTimeZone(config('app.timezone')));
        }

        return $dateTime;
    }
}
