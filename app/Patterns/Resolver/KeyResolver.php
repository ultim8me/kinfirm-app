<?php

namespace App\Patterns\Resolver;

use App\DataAccess\CacheDatabase\ResolvableKeyCache;
use App\Patterns\MethodResponses\FetchMethodResponse;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KeyResolver
 *
 * @package App\Patterns\Resolver
 */
class KeyResolver
{
    public function __construct(
        private array              $resolvableKeys,
        private ResolvableKeyCache $resolvableKeyCache,
        private Model              $model,
    ){}

    /**
     * @param IResolvableKey $dto
     * @return FetchMethodResponse
     * @throws \RedisException
     */
    public function resolveId(IResolvableKey $dto): FetchMethodResponse
    {
        if ($dto->getId()) {
            return FetchMethodResponse::found();
        }

        /*
         * Check Cache
         */
        $id = null;
        foreach ($this->resolvableKeys as $resolvableKey) {

            try {
                $value = $dto->{"get".ucfirst($resolvableKey)}();
            } catch (\Exception|\Throwable $e) {
                continue;
            }

            $methodResponse = $this->resolvableKeyCache->fetchIdByKeyValue($resolvableKey, $value, $id);
            if ($methodResponse->isFound() && $id >= 1) {
                $dto->setId($id);
                return FetchMethodResponse::found();
            }
        }

        /*
         * Check Database
         */
        foreach ($this->resolvableKeys as $resolvableKey) {

            try {
                $value = $dto->{"get".ucfirst($resolvableKey)}();
            } catch (\Exception|\Throwable $e) {
                continue;
            }

            $model = $this->model->where($resolvableKey, $value)->first();

            if ($model) {
                $dto->setId($model->id);
                $this->resolvableKeyCache->createKeyValueToIdCache(
                    $resolvableKey,
                    $value,
                    $dto->getId(),
                );
                $this->resolvableKeyCache->createIdToKeyValueCache(
                    $dto->getId(),
                    $resolvableKey,
                    $value
                );
                return FetchMethodResponse::found();
            }
        }

        return FetchMethodResponse::notFound();
    }

}
