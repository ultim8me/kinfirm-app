<?php

namespace App\DataAccess\CacheDatabase;

use App\Constants\DatabaseConnections;
use App\Patterns\Log\LogException;
use App\Patterns\Log\LogMessage;
use App\Patterns\MethodResponses\CreateMethodResponse;
use App\Patterns\MethodResponses\FetchMethodResponse;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ResolvableKeyCache
 * @package App\DataAccess\CacheDatabase
 */
class ResolvableKeyCache
{
    private const ID_TO_KEY_CACHE_CREATE_FAILED = 'ID_TO_KEY_CACHE_CREATE_FAILED';
    private const KEY_TO_ID_CACHE_CREATE_FAILED = 'KEY_TO_ID_CACHE_CREATE_FAILED';
    private const KEY_VALUE_FETCH_BY_ID_FAILED = 'KEY_VALUE_FETCH_BY_ID_FAILED';
    private const ID_FETCH_BY_KEY_VALUE_FAILED = 'ID_FETCH_BY_KEY_VALUE_FAILED';


    /**
     * @param string $prefix
     */
    public function __construct(private readonly string $prefix)
    {}

    /**
     * @param int $id
     * @param string $key
     * @param mixed $value
     * @return CreateMethodResponse
     */
    public function createIdToKeyValueCache(int $id, string $key, mixed $value): CreateMethodResponse
    {
        $cacheKey = $this->getIdToKeyValueCacheKey($id, $key);

        try {
            $result = Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->put($cacheKey, $value, now()->addDay());
        } catch (\Exception $exception) {
            return CreateMethodResponse::error([
                new LogMessage(static::ID_TO_KEY_CACHE_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        if ($result) {
            return CreateMethodResponse::created();
        }

        return CreateMethodResponse::error([new LogMessage("Failed to store Cache Value for key \"$cacheKey\"")]);
    }

    /**
     * @param int $id
     * @param string $key
     * @return string
     */
    private function getIdToKeyValueCacheKey(int $id, string $key): string
    {
        return sprintf("%s.id_to_%s.%d", $this->prefix, $key, $id);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $id
     * @return CreateMethodResponse
     */
    public function createKeyValueToIdCache(string $key, string $value, int $id): CreateMethodResponse
    {
        $cacheKey = $this->getKeyValueToIdCacheKey($key, $value);

        try {
            $result = Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->put($cacheKey, $id, now()->addDay());
        } catch (\Exception $exception) {
            return CreateMethodResponse::error([
                new LogMessage(static::KEY_TO_ID_CACHE_CREATE_FAILED),
                new LogException($exception)
            ]);
        }

        if ($result) {
            return CreateMethodResponse::created();
        }

        return CreateMethodResponse::error([new LogMessage("Failed to store Cache Value for key \"$cacheKey\"")]);
    }

    private function getKeyValueToIdCacheKey(string $key, string $value): string
    {
        return sprintf("%s.%s_key_to_id.%s", $this->prefix, $key, $value);
    }

    /**
     * @param int $id
     * @param int $key
     * @param string|null $value
     * @return FetchMethodResponse
     */
    public function fetchKeyValueById(int $id, int $key, ?string &$value): FetchMethodResponse
    {
        $cacheKey = $this->getIdToKeyValueCacheKey($id, $key);

        try {
            $result = Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->get($cacheKey);
        } catch (\Exception|InvalidArgumentException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(static::KEY_VALUE_FETCH_BY_ID_FAILED),
                new LogException($exception)
            ]);
        }

        if (!$result) {
            return FetchMethodResponse::notFound();
        }

        $value = $result;

        return FetchMethodResponse::found();
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|null $id
     * @return FetchMethodResponse
     */
    public function fetchIdByKeyValue(string $key, string $value, ?int &$id): FetchMethodResponse
    {
        $cacheKey = $this->getKeyValueToIdCacheKey($key, $value);
        try {
            $result = Cache::store(DatabaseConnections::CACHE_CONNECTION_NAME)->get($cacheKey);
        } catch (\Exception|InvalidArgumentException $exception) {
            return FetchMethodResponse::error([
                new LogMessage(static::ID_FETCH_BY_KEY_VALUE_FAILED),
                new LogException($exception)
            ]);
        }

        if (!$result) {
            return FetchMethodResponse::notFound();
        }

        $id = intval($result);

        return FetchMethodResponse::found();
    }

}
