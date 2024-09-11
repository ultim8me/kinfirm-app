<?php

namespace App\Services;

use App\Constants\DatabaseConnections;
use App\Constants\ErrorMessages;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\DeleteMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class CacheService
 * @package App\Services
 */
class CacheService
{
    /**
     * @param string|null $key
     * @param mixed $value
     * @return FetchMethodResponse
     */
    public function fetchValueByKey(?string $key, mixed &$value): FetchMethodResponse
    {
        try {

            if (is_null($key)) {
                return FetchMethodResponse::notFound();
            }

            $value = Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->get($key);

            if (!$value) {
                return FetchMethodResponse::notFound();
            }

        } catch (\Exception|InvalidArgumentException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(ErrorMessages::CACHE_GET_FAILED),
                new LogException($exception)
            ]);
        }

        return FetchMethodResponse::found();
    }

    /**
     * @param string|null $key
     * @param mixed $value
     * @param \DateTimeInterface|\DateInterval|int|null $ttl
     * @return CreateMethodResponse
     */
    public function create(
        ?string $key,
        mixed $value,
        \DateTimeInterface|\DateInterval|int|null $ttl = null
    ): CreateMethodResponse {

        if (is_null($key)) {
            return CreateMethodResponse::error([new LogMessage(__METHOD__ . " KEY is null")]);
        }

        try {

            Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->put($key, $value, $ttl);

        } catch (\Exception $exception) {
            return CreateMethodResponse::error([
                new LogMessage(ErrorMessages::CACHE_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        return CreateMethodResponse::created();
    }

    /**
     * @return DeleteMethodResponse
     */
    public function flush(): DeleteMethodResponse
    {
        try {
            Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->flush();
        } catch (\Exception $exception) {
            return DeleteMethodResponse::error([
                new LogMessage(ErrorMessages::CACHE_FLUSH_FAILED),
                new LogException($exception)
            ]);
        }

        return DeleteMethodResponse::deleted();
    }

    /**
     * @param string $key
     * @return DeleteMethodResponse
     */
    public function deleteByKey(string $key): DeleteMethodResponse
    {
        try {
            Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->forget($key);
        } catch (\Exception $exception) {
            return DeleteMethodResponse::error([
                new LogMessage(ErrorMessages::CACHE_DELETE_BY_KEY_FAILED),
                new LogException($exception)
            ]);
        }

        return DeleteMethodResponse::deleted();
    }
}
